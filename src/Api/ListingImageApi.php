<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;

use function is_array;
use function sprintf;

final class ListingImageApi implements ListingImageApiInterface
{
    /**
     * @var array<int, array<int, ListingImageInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private ListingImagesTransformerInterface $listingImagesTransformer;
    private ListingImageTransformerInterface $listingImageTransformer;

    /**
     * @var array<string, ListingImageInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingImageTransformerInterface $listingImageTransformer, ListingImagesTransformerInterface $listingImagesTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingImageTransformer = $listingImageTransformer;
        $this->listingImagesTransformer = $listingImagesTransformer;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingImageInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (isset($this->cache[$listingId])) {
                return $this->cache[$listingId];
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $listingImages = $this->listingImagesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache[$listingId] = $listingImages;

        return $listingImages;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $listingId, int $listingImageId, bool $skipCache = false): ListingImageInterface
    {
        $cacheKey = sprintf('%d:%d', $listingId, $listingImageId);
        if (!$skipCache) {
            if (isset($this->oneCache[$cacheKey])) {
                return $this->oneCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId, $listingImageId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingImage = $this->listingImageTransformer->transform($data);
        $this->oneCache[$cacheKey] = $listingImage;

        return $listingImage;
    }
}
