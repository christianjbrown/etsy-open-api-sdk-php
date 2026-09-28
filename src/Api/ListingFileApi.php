<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\RequestContext;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\MultipartFormDataBuilderInterface;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Model\UploadListingFileRequestInterface;
use ChristianBrown\Etsy\Serializer\UploadListingFileRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingFileTransformerInterface;

use function array_merge;
use function is_array;
use function sprintf;

final class ListingFileApi implements ListingFileApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private JsonToArrayTransformerInterface $jsonToArrayTransformer;
    private ListingFilesTransformerInterface $listingFilesTransformer;
    private ListingFileTransformerInterface $listingFileTransformer;
    private MultipartFormDataBuilderInterface $multipartFormDataBuilder;
    private ResponseCacheInterface $oneCache;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private UploadListingFileRequestSerializerInterface $uploadListingFileRequestSerializer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ListingFileTransformerInterface $listingFileTransformer, ListingFilesTransformerInterface $listingFilesTransformer, MultipartFormDataBuilderInterface $multipartFormDataBuilder, JsonToArrayTransformerInterface $jsonToArrayTransformer, UploadListingFileRequestSerializerInterface $uploadListingFileRequestSerializer, ResponseCacheInterface $cache, ResponseCacheInterface $oneCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->listingFileTransformer = $listingFileTransformer;
        $this->listingFilesTransformer = $listingFilesTransformer;
        $this->multipartFormDataBuilder = $multipartFormDataBuilder;
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
        $this->uploadListingFileRequestSerializer = $uploadListingFileRequestSerializer;
        $this->cache = $cache;
        $this->oneCache = $oneCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $listingId, int $listingFileId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, $listingFileId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->delete((string) $listingId);
        $this->oneCache->delete(sprintf('%d:%d', $listingId, $listingFileId));
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ListingFileInterface>
     */
    public function getMultiple(int $listingId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->cache->has((string) $listingId)) {
                /**
                 * @var array<int, ListingFileInterface> $cached
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
        $listingFiles = $this->listingFilesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set((string) $listingId, $listingFiles);

        return $listingFiles;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $listingId, int $listingFileId, bool $skipCache = false): ListingFileInterface
    {
        $cacheKey = sprintf('%d:%d', $listingId, $listingFileId);
        if (!$skipCache) {
            if ($this->oneCache->has($cacheKey)) {
                /**
                 * @var ListingFileInterface $cached
                 */
                $cached = $this->oneCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, $listingFileId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingFile = $this->listingFileTransformer->transform($data);
        $this->oneCache->set($cacheKey, $listingFile);

        return $listingFile;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function upload(int $listingId, UploadListingFileRequestInterface $uploadListingFileRequest): ListingFileInterface
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId, $listingId);
        $boundary = $this->multipartFormDataBuilder->generateBoundary();
        $body = $this->multipartFormDataBuilder->build($boundary, $this->uploadListingFileRequestSerializer->serialize($uploadListingFileRequest), $uploadListingFileRequest->getFile());
        $headers = array_merge($this->credentials->toHeaders(), [ApiRequestSenderInterface::HEADER_CONTENT_TYPE => $this->multipartFormDataBuilder->toContentTypeHeaderValue($boundary)]);
        $contents = $this->apiRequestSender->post($url, [], $headers, $body);
        $data = $this->jsonToArrayTransformer->transform($contents, new RequestContext(ApiRequestSenderInterface::METHOD_POST, $url));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingFile = $this->listingFileTransformer->transform($data);
        $this->cache->delete((string) $listingId);
        $this->oneCache->set(sprintf('%d:%d', $listingId, $listingFile->getListingFileId()), $listingFile);

        return $listingFile;
    }
}
