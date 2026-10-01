<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\ReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;
use ChristianBrown\Etsy\Model\UpdateListingPropertyRequestInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingPropertyRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformerInterface;

use function is_array;
use function sprintf;

final class ListingPropertyApi implements ListingPropertyApiInterface
{
    private ReadApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer;
    private ListingPropertyValueTransformerInterface $listingPropertyValueTransformer;
    private ResponseCacheInterface $oneCache;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateListingPropertyRequestSerializerInterface $updateListingPropertyRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ReadApiRequestSenderInterface $apiRequestSender, ListingPropertyValueTransformerInterface $listingPropertyValueTransformer, ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer, UpdateListingPropertyRequestSerializerInterface $updateListingPropertyRequestSerializer, ResponseCacheInterface $cache, ResponseCacheInterface $oneCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingPropertyValueTransformer = $listingPropertyValueTransformer;
        $this->listingPropertyValuesTransformer = $listingPropertyValuesTransformer;
        $this->updateListingPropertyRequestSerializer = $updateListingPropertyRequestSerializer;
        $this->cache = $cache;
        $this->oneCache = $oneCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId, int $propertyId): void
    {
        $url = sprintf(self::API_URL_WRITE_SPRINTF, $this->shopId, $listingId, $propertyId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->delete((string) $listingId);
        $this->oneCache->delete(sprintf('%d:%d', $listingId, $propertyId));
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingPropertyValueInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->cache->has((string) $listingId)) {
                /**
                 * @var array<int, ListingPropertyValueInterface> $cached
                 */
                $cached = $this->cache->get((string) $listingId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $propertyValues = $this->listingPropertyValuesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set((string) $listingId, $propertyValues);

        return $propertyValues;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $listingId, int $propertyId, bool $skipCache = false): ListingPropertyValueInterface
    {
        $cacheKey = sprintf('%d:%d', $listingId, $propertyId);
        if (!$skipCache) {
            if ($this->oneCache->has($cacheKey)) {
                /**
                 * @var ListingPropertyValueInterface $cached
                 */
                $cached = $this->oneCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId, $propertyId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $propertyValue = $this->listingPropertyValueTransformer->transform($data);
        $this->oneCache->set($cacheKey, $propertyValue);

        return $propertyValue;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $listingId, int $propertyId, UpdateListingPropertyRequestInterface $updateListingPropertyRequest): ListingPropertyValueInterface
    {
        $url = sprintf(self::API_URL_WRITE_SPRINTF, $this->shopId, $listingId, $propertyId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->updateListingPropertyRequestSerializer->serialize($updateListingPropertyRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $propertyValue = $this->listingPropertyValueTransformer->transform($data);
        $this->cache->delete((string) $listingId);
        $this->oneCache->set(sprintf('%d:%d', $listingId, $propertyId), $propertyValue);

        return $propertyValue;
    }
}
