<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingVideoInterface;
use ChristianBrown\Etsy\Transformer\ListingVideosTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingVideoTransformerInterface;

use function is_array;
use function sprintf;

final class ListingVideoApi implements ListingVideoApiInterface
{
    /**
     * @var array<int, array<int, ListingVideoInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private ListingVideosTransformerInterface $listingVideosTransformer;
    private ListingVideoTransformerInterface $listingVideoTransformer;

    /**
     * @var array<string, ListingVideoInterface>
     */
    private array $oneCache = [];
    private JsonApiRequestSenderInterface $requestSender;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ListingVideoTransformerInterface $listingVideoTransformer, ListingVideosTransformerInterface $listingVideosTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->listingVideoTransformer = $listingVideoTransformer;
        $this->listingVideosTransformer = $listingVideosTransformer;
        $this->credentials = $credentials;
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
}
