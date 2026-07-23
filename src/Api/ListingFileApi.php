<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingFileInterface;
use ChristianBrown\Etsy\Transformer\ListingFilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingFileTransformerInterface;

use function is_array;
use function sprintf;

final class ListingFileApi implements ListingFileApiInterface
{
    /**
     * @var array<int, array<int, ListingFileInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private ListingFilesTransformerInterface $listingFilesTransformer;
    private ListingFileTransformerInterface $listingFileTransformer;

    /**
     * @var array<string, ListingFileInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingFileTransformerInterface $listingFileTransformer, ListingFilesTransformerInterface $listingFilesTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->listingFileTransformer = $listingFileTransformer;
        $this->listingFilesTransformer = $listingFilesTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
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
        $listingFiles = $this->listingFilesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache[$listingId] = $listingFiles;

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
            if (isset($this->oneCache[$cacheKey])) {
                return $this->oneCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $listingId, $listingFileId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $listingFile = $this->listingFileTransformer->transform($data);
        $this->oneCache[$cacheKey] = $listingFile;

        return $listingFile;
    }
}
