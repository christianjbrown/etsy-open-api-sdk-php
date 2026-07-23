<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductOfferingTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryProductTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingInventoryTransformerInterface;

use function sprintf;

final class ListingInventoryApi implements ListingInventoryApiInterface
{
    /**
     * @var array<int, ListingInventoryInterface>
     */
    private array $byListingIdCache = [];
    private CredentialsInterface $credentials;
    private ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer;
    private ListingInventoryProductTransformerInterface $listingInventoryProductTransformer;
    private ListingInventoryTransformerInterface $listingInventoryTransformer;

    /**
     * @var array<string, ListingInventoryProductOfferingInterface>
     */
    private array $offeringCache = [];

    /**
     * @var array<string, ListingInventoryProductInterface>
     */
    private array $productCache = [];
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingInventoryTransformerInterface $listingInventoryTransformer, ListingInventoryProductTransformerInterface $listingInventoryProductTransformer, ListingInventoryProductOfferingTransformerInterface $listingInventoryProductOfferingTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingInventoryTransformer = $listingInventoryTransformer;
        $this->listingInventoryProductTransformer = $listingInventoryProductTransformer;
        $this->listingInventoryProductOfferingTransformer = $listingInventoryProductOfferingTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getByListingId(int $listingId, bool $skipCache = false): ListingInventoryInterface
    {
        if (!$skipCache) {
            if (isset($this->byListingIdCache[$listingId])) {
                return $this->byListingIdCache[$listingId];
            }
        }

        $url = sprintf(self::API_URL_BY_LISTING_ID_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $inventory = $this->listingInventoryTransformer->transform($data);
        $this->byListingIdCache[$listingId] = $inventory;

        return $inventory;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOffering(int $listingId, int $productId, int $offeringId, bool $skipCache = false): ListingInventoryProductOfferingInterface
    {
        $cacheKey = sprintf('%d:%d:%d', $listingId, $productId, $offeringId);
        if (!$skipCache) {
            if (isset($this->offeringCache[$cacheKey])) {
                return $this->offeringCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_OFFERING_SPRINTF, $listingId, $productId, $offeringId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $offering = $this->listingInventoryProductOfferingTransformer->transform($data);
        $this->offeringCache[$cacheKey] = $offering;

        return $offering;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getProduct(int $listingId, int $productId, bool $skipCache = false): ListingInventoryProductInterface
    {
        $cacheKey = sprintf('%d:%d', $listingId, $productId);
        if (!$skipCache) {
            if (isset($this->productCache[$cacheKey])) {
                return $this->productCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_PRODUCT_SPRINTF, $listingId, $productId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $product = $this->listingInventoryProductTransformer->transform($data);
        $this->productCache[$cacheKey] = $product;

        return $product;
    }
}
