<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\ApiClient\RequestContext;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\ListingImageInterface;
use ChristianBrown\Etsy\Model\UploadListingImageRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingImageRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingImagesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingImageTransformerInterface;

use function array_merge;
use function is_array;
use function sprintf;

final class ListingImageApi implements ListingImageApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private JsonToArrayTransformerInterface $jsonToArrayTransformer;
    private ListingImagesTransformerInterface $listingImagesTransformer;
    private ListingImageTransformerInterface $listingImageTransformer;
    private MultipartFormDataBuilderInterface $multipartFormDataBuilder;
    private ResponseCacheInterface $oneCache;
    private JsonReadApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UploadListingImageRequestSerializerInterface $uploadListingImageRequestSerializer;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingImageTransformerInterface $listingImageTransformer, ListingImagesTransformerInterface $listingImagesTransformer, MultipartFormDataBuilderInterface $multipartFormDataBuilder, JsonToArrayTransformerInterface $jsonToArrayTransformer, UploadListingImageRequestSerializerInterface $uploadListingImageRequestSerializer, ResponseCacheInterface $cache, ResponseCacheInterface $oneCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingImageTransformer = $listingImageTransformer;
        $this->listingImagesTransformer = $listingImagesTransformer;
        $this->multipartFormDataBuilder = $multipartFormDataBuilder;
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
        $this->uploadListingImageRequestSerializer = $uploadListingImageRequestSerializer;
        $this->cache = $cache;
        $this->oneCache = $oneCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId, int $listingImageId): void
    {
        $url = sprintf(self::API_URL_WRITE_ONE_SPRINTF, $this->shopId, $listingId, $listingImageId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->delete((string) $listingId);
        $this->oneCache->delete(sprintf('%d:%d', $listingId, $listingImageId));
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
            if ($this->cache->has((string) $listingId)) {
                /**
                 * @var array<int, ListingImageInterface> $cached
                 */
                $cached = $this->cache->get((string) $listingId);

                return $cached;
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
        $this->cache->set((string) $listingId, $listingImages);

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
            if ($this->oneCache->has($cacheKey)) {
                /**
                 * @var ListingImageInterface $cached
                 */
                $cached = $this->oneCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId, $listingImageId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingImage = $this->listingImageTransformer->transform($data);
        $this->oneCache->set($cacheKey, $listingImage);

        return $listingImage;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function upload(int $listingId, UploadListingImageRequestInterface $uploadListingImageRequest): ListingImageInterface
    {
        $url = sprintf(self::API_URL_WRITE_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $boundary = $this->multipartFormDataBuilder->generateBoundary();
        $body = $this->multipartFormDataBuilder->build($boundary, $this->uploadListingImageRequestSerializer->serialize($uploadListingImageRequest), $uploadListingImageRequest->getImage());
        $headers = array_merge($this->credentials->toHeaders(), [ApiRequestSenderInterface::HEADER_CONTENT_TYPE => $this->multipartFormDataBuilder->toContentTypeHeaderValue($boundary)]);
        $contents = $this->apiRequestSender->post($url, [], $headers, $body);
        $data = $this->jsonToArrayTransformer->transform($contents, new RequestContext(ApiRequestSenderInterface::METHOD_POST, $url));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingImage = $this->listingImageTransformer->transform($data);
        $this->cache->delete((string) $listingId);
        $this->oneCache->set(sprintf('%d:%d', $listingId, $listingImage->getListingImageId()), $listingImage);

        return $listingImage;
    }
}
