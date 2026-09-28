<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;

use function array_filter;
use function array_map;
use function implode;
use function is_array;
use function sprintf;

final class ShopListingApi implements ShopListingApiInterface
{
    private ResponseCacheInterface $activeByShopCache;
    private ResponseCacheInterface $activeCache;
    private ApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $byIdCache;
    private ResponseCacheInterface $byListingIdsCache;
    private ResponseCacheInterface $byReceiptCache;
    private ResponseCacheInterface $byReturnPolicyCache;
    private ResponseCacheInterface $byShopCache;
    private ResponseCacheInterface $byShopSectionIdsCache;
    private CreateDraftListingRequestSerializerInterface $createDraftListingRequestSerializer;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $featuredByShopCache;
    private ListingsTransformerInterface $listingsTransformer;
    private ListingTransformerInterface $listingTransformer;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateListingRequestSerializerInterface $updateListingRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingTransformerInterface $listingTransformer, ListingsTransformerInterface $listingsTransformer, CreateDraftListingRequestSerializerInterface $createDraftListingRequestSerializer, UpdateListingRequestSerializerInterface $updateListingRequestSerializer, ResponseCacheInterface $activeByShopCache, ResponseCacheInterface $activeCache, ResponseCacheInterface $byIdCache, ResponseCacheInterface $byListingIdsCache, ResponseCacheInterface $byReceiptCache, ResponseCacheInterface $byReturnPolicyCache, ResponseCacheInterface $byShopCache, ResponseCacheInterface $byShopSectionIdsCache, ResponseCacheInterface $featuredByShopCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingTransformer = $listingTransformer;
        $this->listingsTransformer = $listingsTransformer;
        $this->createDraftListingRequestSerializer = $createDraftListingRequestSerializer;
        $this->updateListingRequestSerializer = $updateListingRequestSerializer;
        $this->activeByShopCache = $activeByShopCache;
        $this->activeCache = $activeCache;
        $this->byIdCache = $byIdCache;
        $this->byListingIdsCache = $byListingIdsCache;
        $this->byReceiptCache = $byReceiptCache;
        $this->byReturnPolicyCache = $byReturnPolicyCache;
        $this->byShopCache = $byShopCache;
        $this->byShopSectionIdsCache = $byShopSectionIdsCache;
        $this->featuredByShopCache = $featuredByShopCache;
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
        $this->byIdCache->set(self::buildByIdCacheKey($listing->getListingId(), null, null, null), $listing);
        $this->activeByShopCache->clear();
        $this->byShopCache->clear();
        $this->featuredByShopCache->clear();

        return $listing;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId): void
    {
        $url = sprintf(self::API_URL_BY_ID_SPRINTF, $listingId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->byIdCache->delete((string) $listingId);
        $this->activeCache->clear();
        $this->activeByShopCache->clear();
        $this->byShopCache->clear();
        $this->byShopSectionIdsCache->clear();
        $this->byListingIdsCache->clear();
        $this->byReceiptCache->clear();
        $this->byReturnPolicyCache->clear();
        $this->featuredByShopCache->clear();
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function findActive(?string $keywords = null, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?float $minPrice = null, ?float $maxPrice = null, ?int $taxonomyId = null, ?string $shopLocation = null, ?bool $isSafe = null, ?string $currency = null, ?string $buyerCountry = null): array
    {
        $cacheKey = self::buildActiveCacheKey($keywords, $limit, $offset, $sortOn, $sortOrder, $minPrice, $maxPrice, $taxonomyId, $shopLocation, $isSafe, $currency, $buyerCountry);
        if (!$skipCache) {
            if ($this->activeCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->activeCache->get($cacheKey);

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_ACTIVE, self::buildActiveQuery($keywords, $limit, $offset, $sortOn, $sortOrder, $minPrice, $maxPrice, $taxonomyId, $shopLocation, $isSafe, $currency, $buyerCountry), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->activeCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function findActiveByShop(int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null): array
    {
        $cacheKey = sprintf('%d:%d:%s:%s', $limit, $offset, $sortOn ?? '', $sortOrder ?? '');
        if (!$skipCache) {
            if ($this->activeByShopCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->activeByShopCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ACTIVE_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildSortablePaginationQuery($limit, $offset, $sortOn, $sortOrder), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->activeByShopCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getById(int $listingId, bool $skipCache = false, ?string $includes = null, ?string $language = null, ?bool $allowSuggestedTitle = null): ListingInterface
    {
        $cacheKey = self::buildByIdCacheKey($listingId, $includes, $language, $allowSuggestedTitle);
        if (!$skipCache) {
            if ($this->byIdCache->has($cacheKey)) {
                /**
                 * @var ListingInterface $cached
                 */
                $cached = $this->byIdCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_ID_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, self::buildByIdQuery($includes, $language, $allowSuggestedTitle), $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listing = $this->listingTransformer->transform($data);
        $this->byIdCache->set($cacheKey, $listing);

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
    public function getByListingIds(array $listingIds, bool $skipCache = false, ?string $includes = null, ?string $currency = null, ?string $buyerCountry = null, ?bool $legacy = null): array
    {
        $cacheKey = self::buildBatchCacheKey($listingIds, $includes, $currency, $buyerCountry, $legacy);
        if (!$skipCache) {
            if ($this->byListingIdsCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->byListingIdsCache->get($cacheKey);

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_BATCH, self::buildBatchQuery($listingIds, $includes, $currency, $buyerCountry, $legacy), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byListingIdsCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByReceipt(int $receiptId, int $limit = 25, int $offset = 0, bool $skipCache = false, ?bool $legacy = null): array
    {
        $cacheKey = self::buildByReceiptCacheKey($receiptId, $limit, $offset, $legacy);
        if (!$skipCache) {
            if ($this->byReceiptCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->byReceiptCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_RECEIPT_SPRINTF, $this->shopId, $receiptId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset, $legacy), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byReceiptCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByReturnPolicy(int $returnPolicyId, bool $skipCache = false, ?bool $legacy = null): array
    {
        $cacheKey = self::buildByReturnPolicyCacheKey($returnPolicyId, $legacy);
        if (!$skipCache) {
            if ($this->byReturnPolicyCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->byReturnPolicyCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_RETURN_POLICY_SPRINTF, $this->shopId, $returnPolicyId);
        $data = $this->requestSender->get($url, self::buildLegacyQuery($legacy), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byReturnPolicyCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getByShop(?string $state = null, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?string $includes = null): array
    {
        $cacheKey = sprintf('%s:%d:%d:%s:%s:%s', $state ?? '', $limit, $offset, $sortOn ?? '', $sortOrder ?? '', $includes ?? '');
        if (!$skipCache) {
            if ($this->byShopCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->byShopCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildShopQuery($state, $limit, $offset, $sortOn, $sortOrder, $includes), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShopCache->set($cacheKey, $listings);

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
    public function getByShopSectionIds(array $shopSectionIds, int $limit = 25, int $offset = 0, bool $skipCache = false, ?string $sortOn = null, ?string $sortOrder = null, ?bool $legacy = null): array
    {
        $cacheKey = self::buildShopSectionCacheKey($shopSectionIds, $limit, $offset, $sortOn, $sortOrder, $legacy);
        if (!$skipCache) {
            if ($this->byShopSectionIdsCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->byShopSectionIdsCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_SHOP_SECTIONS_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildShopSectionQuery($shopSectionIds, $limit, $offset, $sortOn, $sortOrder, $legacy), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShopSectionIdsCache->set($cacheKey, $listings);

        return $listings;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingInterface>
     */
    public function getFeaturedByShop(int $limit = 25, int $offset = 0, bool $skipCache = false, ?bool $legacy = null): array
    {
        $cacheKey = self::buildPaginationCacheKey($limit, $offset, $legacy);
        if (!$skipCache) {
            if ($this->featuredByShopCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingInterface> $cached
                 */
                $cached = $this->featuredByShopCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_FEATURED_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildPaginationQuery($limit, $offset, $legacy), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->featuredByShopCache->set($cacheKey, $listings);

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
        $this->byIdCache->set(self::buildByIdCacheKey($listingId, null, null, null), $listing);
        $this->activeCache->clear();
        $this->activeByShopCache->clear();
        $this->byShopCache->clear();
        $this->byShopSectionIdsCache->clear();
        $this->byListingIdsCache->clear();
        $this->byReceiptCache->clear();
        $this->byReturnPolicyCache->clear();
        $this->featuredByShopCache->clear();

        return $listing;
    }

    private static function buildActiveCacheKey(?string $keywords, int $limit, int $offset, ?string $sortOn, ?string $sortOrder, ?float $minPrice, ?float $maxPrice, ?int $taxonomyId, ?string $shopLocation, ?bool $isSafe, ?string $currency, ?string $buyerCountry): string
    {
        $parts = [
            $keywords,
            (string) $limit,
            (string) $offset,
            $sortOn,
            $sortOrder,
            self::encodeOptionalFloat($minPrice),
            self::encodeOptionalFloat($maxPrice),
            self::encodeOptionalInt($taxonomyId),
            $shopLocation,
            self::encodeOptionalBool($isSafe),
            $currency,
            $buyerCountry,
        ];

        return implode(':', array_map(static fn (?string $part): string => $part ?? '', $parts));
    }

    /**
     * @return array<string, string>
     */
    private static function buildActiveQuery(?string $keywords, int $limit, int $offset, ?string $sortOn, ?string $sortOrder, ?float $minPrice, ?float $maxPrice, ?int $taxonomyId, ?string $shopLocation, ?bool $isSafe, ?string $currency, ?string $buyerCountry): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_KEYWORDS => $keywords,
                self::KEY_SORT_ON => $sortOn,
                self::KEY_SORT_ORDER => $sortOrder,
                self::KEY_MIN_PRICE => self::encodeOptionalFloat($minPrice),
                self::KEY_MAX_PRICE => self::encodeOptionalFloat($maxPrice),
                self::KEY_TAXONOMY_ID => self::encodeOptionalInt($taxonomyId),
                self::KEY_SHOP_LOCATION => $shopLocation,
                self::KEY_IS_SAFE => self::encodeOptionalBool($isSafe),
                self::KEY_CURRENCY => $currency,
                self::KEY_BUYER_COUNTRY => $buyerCountry,
            ],
            static fn (?string $value): bool => null !== $value
        );

        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + $optional;
    }

    /**
     * @param array<int, int> $listingIds
     */
    private static function buildBatchCacheKey(array $listingIds, ?string $includes, ?string $currency, ?string $buyerCountry, ?bool $legacy): string
    {
        return sprintf('%s:%s:%s:%s:%s', implode(',', $listingIds), $includes ?? '', $currency ?? '', $buyerCountry ?? '', self::encodeOptionalBool($legacy) ?? '');
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @return array<string, string>
     */
    private static function buildBatchQuery(array $listingIds, ?string $includes, ?string $currency, ?string $buyerCountry, ?bool $legacy): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_INCLUDES => $includes,
                self::KEY_CURRENCY => $currency,
                self::KEY_BUYER_COUNTRY => $buyerCountry,
                self::KEY_LEGACY => self::encodeOptionalBool($legacy),
            ],
            static fn (?string $value): bool => null !== $value
        );

        return [
            self::KEY_LISTING_IDS => implode(',', $listingIds),
        ] + $optional;
    }

    private static function buildByIdCacheKey(int $listingId, ?string $includes, ?string $language, ?bool $allowSuggestedTitle): string
    {
        return sprintf('%d:%s:%s:%s', $listingId, $includes ?? '', $language ?? '', null === $allowSuggestedTitle ? '' : (int) $allowSuggestedTitle);
    }

    /**
     * @return array<string, string>
     */
    private static function buildByIdQuery(?string $includes, ?string $language, ?bool $allowSuggestedTitle): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_INCLUDES => $includes,
                self::KEY_LANGUAGE => $language,
                self::KEY_ALLOW_SUGGESTED_TITLE => self::encodeOptionalBool($allowSuggestedTitle),
            ],
            static fn (?string $value): bool => null !== $value
        );

        return $optional;
    }

    private static function buildByReceiptCacheKey(int $receiptId, int $limit, int $offset, ?bool $legacy): string
    {
        return sprintf('%d:%d:%d:%s', $receiptId, $limit, $offset, self::encodeOptionalBool($legacy) ?? '');
    }

    private static function buildByReturnPolicyCacheKey(int $returnPolicyId, ?bool $legacy): string
    {
        return sprintf('%d:%s', $returnPolicyId, self::encodeOptionalBool($legacy) ?? '');
    }

    /**
     * @return array<string, string>
     */
    private static function buildLegacyQuery(?bool $legacy): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [self::KEY_LEGACY => self::encodeOptionalBool($legacy)],
            static fn (?string $value): bool => null !== $value
        );

        return $optional;
    }

    private static function buildPaginationCacheKey(int $limit, int $offset, ?bool $legacy): string
    {
        return sprintf('%d:%d:%s', $limit, $offset, self::encodeOptionalBool($legacy) ?? '');
    }

    /**
     * @return array<string, string>
     */
    private static function buildPaginationQuery(int $limit, int $offset, ?bool $legacy = null): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + self::buildLegacyQuery($legacy);
    }

    /**
     * @return array<string, string>
     */
    private static function buildShopQuery(?string $state, int $limit, int $offset, ?string $sortOn, ?string $sortOrder, ?string $includes): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_STATE => $state,
                self::KEY_SORT_ON => $sortOn,
                self::KEY_SORT_ORDER => $sortOrder,
                self::KEY_INCLUDES => $includes,
            ],
            static fn (?string $value): bool => null !== $value
        );

        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + $optional;
    }

    /**
     * @param array<int, int> $shopSectionIds
     */
    private static function buildShopSectionCacheKey(array $shopSectionIds, int $limit, int $offset, ?string $sortOn, ?string $sortOrder, ?bool $legacy): string
    {
        return sprintf('%s:%d:%d:%s:%s:%s', implode(',', $shopSectionIds), $limit, $offset, $sortOn ?? '', $sortOrder ?? '', self::encodeOptionalBool($legacy) ?? '');
    }

    /**
     * @param array<int, int> $shopSectionIds
     *
     * @return array<string, string>
     */
    private static function buildShopSectionQuery(array $shopSectionIds, int $limit, int $offset, ?string $sortOn, ?string $sortOrder, ?bool $legacy = null): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_SORT_ON => $sortOn,
                self::KEY_SORT_ORDER => $sortOrder,
                self::KEY_LEGACY => self::encodeOptionalBool($legacy),
            ],
            static fn (?string $value): bool => null !== $value
        );

        return [
            self::KEY_SHOP_SECTION_IDS => implode(',', $shopSectionIds),
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + $optional;
    }

    /**
     * @return array<string, string>
     */
    private static function buildSortablePaginationQuery(int $limit, int $offset, ?string $sortOn, ?string $sortOrder): array
    {
        /**
         * @var array<string, string> $optional
         */
        $optional = array_filter(
            [
                self::KEY_SORT_ON => $sortOn,
                self::KEY_SORT_ORDER => $sortOrder,
            ],
            static fn (?string $value): bool => null !== $value
        );

        $query = [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ] + $optional;

        return $query;
    }

    private static function encodeOptionalBool(?bool $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value ? 'true' : 'false';
    }

    private static function encodeOptionalFloat(?float $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return (string) $value;
    }

    private static function encodeOptionalInt(?int $value): ?string
    {
        if (null === $value) {
            return null;
        }

        return (string) $value;
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
