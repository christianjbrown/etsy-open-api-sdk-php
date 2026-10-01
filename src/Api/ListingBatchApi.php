<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformerInterface;

use function implode;
use function is_array;
use function sprintf;

final class ListingBatchApi implements ListingBatchApiInterface
{
    private ResponseCacheInterface $byInventoryCache;
    private ResponseCacheInterface $byShippingCache;
    private CredentialsInterface $credentials;
    private ListingsWithAssociationsTransformerInterface $listingsWithAssociationsTransformer;
    private JsonReadApiRequestSenderInterface $requestSender;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, ListingsWithAssociationsTransformerInterface $listingsWithAssociationsTransformer, ResponseCacheInterface $byInventoryCache, ResponseCacheInterface $byShippingCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingsWithAssociationsTransformer = $listingsWithAssociationsTransformer;
        $this->byInventoryCache = $byInventoryCache;
        $this->byShippingCache = $byShippingCache;
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
            if ($this->byInventoryCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingWithAssociationsInterface> $cached
                 */
                $cached = $this->byInventoryCache->get($cacheKey);

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_INVENTORY, self::buildQuery($listingIds), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byInventoryCache->set($cacheKey, $listings);

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
            if ($this->byShippingCache->has($cacheKey)) {
                /**
                 * @var array<int, ListingWithAssociationsInterface> $cached
                 */
                $cached = $this->byShippingCache->get($cacheKey);

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_SHIPPING, self::buildQuery($listingIds), $this->credentials->toHeaders());

        $listings = $this->handleResults($data);
        $this->byShippingCache->set($cacheKey, $listings);

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
