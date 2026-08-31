<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;

use function implode;
use function is_array;
use function sprintf;

final class ShopListingApi implements ShopListingApiInterface
{
    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $activeByShopCache = [];

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $activeCache = [];
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var array<int, ListingInterface>
     */
    private array $byIdCache = [];

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $byListingIdsCache = [];

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $byReceiptCache = [];

    /**
     * @var array<int, array<int, ListingInterface>>
     */
    private array $byReturnPolicyCache = [];

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $byShopCache = [];

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $byShopSectionIdsCache = [];
    private CreateDraftListingRequestSerializerInterface $createDraftListingRequestSerializer;
    private CredentialsInterface $credentials;

    /**
     * @var array<string, array<int, ListingInterface>>
     */
    private array $featuredByShopCache = [];
    private ListingsTransformerInterface $listingsTransformer;
    private ListingTransformerInterface $listingTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateListingRequestSerializerInterface $updateListingRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingTransformerInterface $listingTransformer, ListingsTransformerInterface $listingsTransformer, CreateDraftListingRequestSerializerInterface $createDraftListingRequestSerializer, UpdateListingRequestSerializerInterface $updateListingRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingTransformer = $listingTransformer;
        $this->listingsTransformer = $listingsTransformer;
        $this->createDraftListingRequestSerializer = $createDraftListingRequestSerializer;
        $this->updateListingRequestSerializer = $updateListingRequestSerializer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(CreateDraftListingRequestInterface $createDraftListingRequest): ListingInterface
    {
        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->createDraftListingRequestSerializer->serialize($createDraftListingRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listing = $this->listingTransformer->transform($data);
        $this->byIdCache[$listing->getListingId()] = $listing;
        $this->activeByShopCache = [];
        $this->byShopCache = [];
        $this->featuredByShopCache = [];

        return $listing;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId): void
    {
        $url = sprintf(self::API_URL_BY_ID_SPRINTF, $listingId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        unset($this->byIdCache[$listingId]);
        $this->activeCache = [];
        $this->activeByShopCache = [];
        $this->byShopCache = [];
        $this->byShopSectionIdsCache = [];
        $this->byListingIdsCache = [];
        $this->byReceiptCache = [];
        $this->byReturnPolicyCache = [];
        $this->featuredByShopCache = [];
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function findActive(?string $keywords = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%d:%d', $keywords ?? '', $limit, $offset);
        if (!$skipCache) {
            if (isset($this->activeCache[$cacheKey])) {
                return $this->activeCache[$cacheKey];
            }
        }

        $data = $this->requestSender->get(self::API_URL_ACTIVE, self::buildActiveQuery($keywords, $limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->activeCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function findActiveByShop(int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d', $limit, $offset);
        if (!$skipCache) {
            if (isset($this->activeByShopCache[$cacheKey])) {
                return $this->activeByShopCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_ACTIVE_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->activeByShopCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getById(int $listingId, bool $skipCache = false): ListingInterface
    {
        if (!$skipCache) {
            if (isset($this->byIdCache[$listingId])) {
                return $this->byIdCache[$listingId];
            }
        }

        $url = sprintf(self::API_URL_BY_ID_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listing = $this->listingTransformer->transform($data);
        $this->byIdCache[$listingId] = $listing;

        return $listing;
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByListingIds(array $listingIds, bool $skipCache = false): array
    {
        $cacheKey = implode(',', $listingIds);
        if (!$skipCache) {
            if (isset($this->byListingIdsCache[$cacheKey])) {
                return $this->byListingIdsCache[$cacheKey];
            }
        }

        $data = $this->requestSender->get(self::API_URL_BATCH, self::buildBatchQuery($listingIds), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byListingIdsCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByReceipt(int $receiptId, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d:%d', $receiptId, $limit, $offset);
        if (!$skipCache) {
            if (isset($this->byReceiptCache[$cacheKey])) {
                return $this->byReceiptCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_BY_RECEIPT_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byReceiptCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByReturnPolicy(int $returnPolicyId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (isset($this->byReturnPolicyCache[$returnPolicyId])) {
                return $this->byReturnPolicyCache[$returnPolicyId];
            }
        }

        $url = sprintf(self::API_URL_BY_RETURN_POLICY_SPRINTF, $this->shopId, $returnPolicyId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byReturnPolicyCache[$returnPolicyId] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByShop(?string $state = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%d:%d', $state ?? '', $limit, $offset);
        if (!$skipCache) {
            if (isset($this->byShopCache[$cacheKey])) {
                return $this->byShopCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildShopQuery($state, $limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShopCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @param array<int, int> $shopSectionIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByShopSectionIds(array $shopSectionIds, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%d:%d', implode(',', $shopSectionIds), $limit, $offset);
        if (!$skipCache) {
            if (isset($this->byShopSectionIdsCache[$cacheKey])) {
                return $this->byShopSectionIdsCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_SHOP_SECTIONS_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildShopSectionQuery($shopSectionIds, $limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShopSectionIdsCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getFeaturedByShop(int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d', $limit, $offset);
        if (!$skipCache) {
            if (isset($this->featuredByShopCache[$cacheKey])) {
                return $this->featuredByShopCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_FEATURED_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->featuredByShopCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $listingId, UpdateListingRequestInterface $updateListingRequest): ListingInterface
    {
        $url = sprintf(self::API_URL_UPDATE_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->patchForm($url, [], $this->credentials->toHeaders(), $this->updateListingRequestSerializer->serialize($updateListingRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listing = $this->listingTransformer->transform($data);
        $this->byIdCache[$listingId] = $listing;
        $this->activeCache = [];
        $this->activeByShopCache = [];
        $this->byShopCache = [];
        $this->byShopSectionIdsCache = [];
        $this->byListingIdsCache = [];
        $this->byReceiptCache = [];
        $this->byReturnPolicyCache = [];
        $this->featuredByShopCache = [];

        return $listing;
    }

    /**
     * @return array<string, string>
     */
    private static function buildActiveQuery(?string $keywords, int $limit, int $offset): array
    {
        $query = [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
        if (null !== $keywords) {
            $query[self::KEY_KEYWORDS] = $keywords;
        }

        return $query;
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @return array<string, string>
     */
    private static function buildBatchQuery(array $listingIds): array
    {
        return [
            self::KEY_LISTING_IDS => implode(',', $listingIds),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function buildPaginationQuery(int $limit, int $offset): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function buildShopQuery(?string $state, int $limit, int $offset): array
    {
        $query = [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
        if (null !== $state) {
            $query[self::KEY_STATE] = $state;
        }

        return $query;
    }

    /**
     * @param array<int, int> $shopSectionIds
     *
     * @return array<string, string>
     */
    private static function buildShopSectionQuery(array $shopSectionIds, int $limit, int $offset): array
    {
        return [
            self::KEY_SHOP_SECTION_IDS => implode(',', $shopSectionIds),
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    private function handleResults(array $data): array
    {
        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }

        return $this->listingsTransformer->transform($data[self::KEY_RESULTS]);
    }
}
