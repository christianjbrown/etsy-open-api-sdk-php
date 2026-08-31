<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\RequestContext;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
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

    /**
     * @var array<int, array<int, ListingImageInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private JsonToArrayTransformerInterface $jsonToArrayTransformer;
    private ListingImagesTransformerInterface $listingImagesTransformer;
    private ListingImageTransformerInterface $listingImageTransformer;
    private MultipartFormDataBuilderInterface $multipartFormDataBuilder;

    /**
     * @var array<string, ListingImageInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UploadListingImageRequestSerializerInterface $uploadListingImageRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingImageTransformerInterface $listingImageTransformer, ListingImagesTransformerInterface $listingImagesTransformer, MultipartFormDataBuilderInterface $multipartFormDataBuilder, JsonToArrayTransformerInterface $jsonToArrayTransformer, UploadListingImageRequestSerializerInterface $uploadListingImageRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingImageTransformer = $listingImageTransformer;
        $this->listingImagesTransformer = $listingImagesTransformer;
        $this->multipartFormDataBuilder = $multipartFormDataBuilder;
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
        $this->uploadListingImageRequestSerializer = $uploadListingImageRequestSerializer;
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

        unset($this->cache[$listingId]);
        unset($this->oneCache[sprintf('%d:%d', $listingId, $listingImageId)]);
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
        unset($this->cache[$listingId]);
        $this->oneCache[sprintf('%d:%d', $listingId, $listingImage->getListingImageId())] = $listingImage;

        return $listingImage;
    }
}
