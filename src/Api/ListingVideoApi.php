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
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Model\UploadListingVideoRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingVideoRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;

use function array_merge;
use function is_array;
use function sprintf;

final class ListingVideoApi implements ListingVideoApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var array<int, array<int, ListingVideoInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private JsonToArrayTransformerInterface $jsonToArrayTransformer;
    private ListingVideosTransformerInterface $listingVideosTransformer;
    private ListingVideoTransformerInterface $listingVideoTransformer;
    private MultipartFormDataBuilderInterface $multipartFormDataBuilder;

    /**
     * @var array<string, ListingVideoInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UploadListingVideoRequestSerializerInterface $uploadListingVideoRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingVideoTransformerInterface $listingVideoTransformer, ListingVideosTransformerInterface $listingVideosTransformer, MultipartFormDataBuilderInterface $multipartFormDataBuilder, JsonToArrayTransformerInterface $jsonToArrayTransformer, UploadListingVideoRequestSerializerInterface $uploadListingVideoRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingVideoTransformer = $listingVideoTransformer;
        $this->listingVideosTransformer = $listingVideosTransformer;
        $this->multipartFormDataBuilder = $multipartFormDataBuilder;
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
        $this->uploadListingVideoRequestSerializer = $uploadListingVideoRequestSerializer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId, int $videoId): void
    {
        $url = sprintf(self::API_URL_WRITE_ONE_SPRINTF, $this->shopId, $listingId, $videoId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        unset($this->cache[$listingId]);
        unset($this->oneCache[sprintf('%d:%d', $listingId, $videoId)]);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingVideoInterface>
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
        $listingVideos = $this->listingVideosTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache[$listingId] = $listingVideos;

        return $listingVideos;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $listingId, int $videoId, bool $skipCache = false): ListingVideoInterface
    {
        $cacheKey = sprintf('%d:%d', $listingId, $videoId);
        if (!$skipCache) {
            if (isset($this->oneCache[$cacheKey])) {
                return $this->oneCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $listingId, $videoId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingVideo = $this->listingVideoTransformer->transform($data);
        $this->oneCache[$cacheKey] = $listingVideo;

        return $listingVideo;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function upload(int $listingId, UploadListingVideoRequestInterface $uploadListingVideoRequest): ListingVideoInterface
    {
        $url = sprintf(self::API_URL_WRITE_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $boundary = $this->multipartFormDataBuilder->generateBoundary();
        $body = $this->multipartFormDataBuilder->build($boundary, $this->uploadListingVideoRequestSerializer->serialize($uploadListingVideoRequest), $uploadListingVideoRequest->getVideo());
        $headers = array_merge($this->credentials->toHeaders(), [ApiRequestSenderInterface::HEADER_CONTENT_TYPE => $this->multipartFormDataBuilder->toContentTypeHeaderValue($boundary)]);
        $contents = $this->apiRequestSender->post($url, [], $headers, $body);
        $data = $this->jsonToArrayTransformer->transform($contents, new RequestContext(ApiRequestSenderInterface::METHOD_POST, $url));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingVideo = $this->listingVideoTransformer->transform($data);
        unset($this->cache[$listingId]);
        $this->oneCache[sprintf('%d:%d', $listingId, $listingVideo->getVideoId())] = $listingVideo;

        return $listingVideo;
    }
}
