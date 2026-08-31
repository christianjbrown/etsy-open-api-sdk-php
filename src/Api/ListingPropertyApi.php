<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
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
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var array<int, array<int, ListingPropertyValueInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer;
    private ListingPropertyValueTransformerInterface $listingPropertyValueTransformer;

    /**
     * @var array<string, ListingPropertyValueInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UpdateListingPropertyRequestSerializerInterface $updateListingPropertyRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingPropertyValueTransformerInterface $listingPropertyValueTransformer, ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer, UpdateListingPropertyRequestSerializerInterface $updateListingPropertyRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingPropertyValueTransformer = $listingPropertyValueTransformer;
        $this->listingPropertyValuesTransformer = $listingPropertyValuesTransformer;
        $this->updateListingPropertyRequestSerializer = $updateListingPropertyRequestSerializer;
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

        unset($this->cache[$listingId], $this->oneCache[sprintf('%d:%d', $listingId, $propertyId)]);
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
            if (isset($this->cache[$listingId])) {
                return $this->cache[$listingId];
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
        $this->cache[$listingId] = $propertyValues;

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
            if (isset($this->oneCache[$cacheKey])) {
                return $this->oneCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId, $propertyId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $propertyValue = $this->listingPropertyValueTransformer->transform($data);
        $this->oneCache[$cacheKey] = $propertyValue;

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
        unset($this->cache[$listingId]);
        $this->oneCache[sprintf('%d:%d', $listingId, $propertyId)] = $propertyValue;

        return $propertyValue;
    }
}
