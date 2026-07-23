<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformerInterface;

use function implode;
use function is_array;
use function sprintf;

final class ListingBatchApi implements ListingBatchApiInterface
{
    /**
     * @var array<string, array<int, ListingWithAssociationsInterface>>
     */
    private array $byInventoryCache = [];

    /**
     * @var array<string, array<int, ListingWithAssociationsInterface>>
     */
    private array $byShippingCache = [];
    private CredentialsInterface $credentials;
    private ListingsWithAssociationsTransformerInterface $listingsWithAssociationsTransformer;
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingsWithAssociationsTransformerInterface $listingsWithAssociationsTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingsWithAssociationsTransformer = $listingsWithAssociationsTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function getInventoryByListingIds(array $listingIds, bool $skipCache = false): array
    {
        $cacheKey = implode(',', $listingIds);
        if (!$skipCache) {
            if (isset($this->byInventoryCache[$cacheKey])) {
                return $this->byInventoryCache[$cacheKey];
            }
        }

        $data = $this->requestSender->get(self::API_URL_INVENTORY, self::buildQuery($listingIds), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byInventoryCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    public function getShippingByListingIds(array $listingIds, bool $skipCache = false): array
    {
        $cacheKey = implode(',', $listingIds);
        if (!$skipCache) {
            if (isset($this->byShippingCache[$cacheKey])) {
                return $this->byShippingCache[$cacheKey];
            }
        }

        $data = $this->requestSender->get(self::API_URL_SHIPPING, self::buildQuery($listingIds), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShippingCache[$cacheKey] = $listings;

        return $listings;
    }

    /**
     * @param array<int, int> $listingIds
     *
     * @return array<string, string>
     */
    private static function buildQuery(array $listingIds): array
    {
        return [
            self::KEY_LISTING_IDS => implode(',', $listingIds),
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingWithAssociationsInterface>
     */
    private function handleResults(array $data): array
    {
        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }

        return $this->listingsWithAssociationsTransformer->transform($data[self::KEY_RESULTS]);
    }
}
