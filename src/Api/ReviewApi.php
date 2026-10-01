<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReviewInterface;
use ChristianBrown\Etsy\Transformer\ReviewsTransformerInterface;

use function is_array;
use function sprintf;

final class ReviewApi implements ReviewApiInterface
{
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $listingCache;
    private JsonReadApiRequestSenderInterface $requestSender;
    private ReviewsTransformerInterface $reviewsTransformer;
    private ResponseCacheInterface $shopCache;
    private int $shopId;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, ReviewsTransformerInterface $reviewsTransformer, ResponseCacheInterface $listingCache, ResponseCacheInterface $shopCache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->reviewsTransformer = $reviewsTransformer;
        $this->listingCache = $listingCache;
        $this->shopCache = $shopCache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ReviewInterface>
     */
    public function getByListing(int $listingId, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d:%d', $listingId, $limit, $offset);
        if (!$skipCache) {
            if ($this->listingCache->has($cacheKey)) {
                /**
                 * @var array<int, ReviewInterface> $cached
                 */
                $cached = $this->listingCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_LISTING_SPRINTF, $listingId);
        $data = $this->requestSender->get($url, self::buildQuery(null, null, $limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $reviews = $this->reviewsTransformer->transform($data[self::KEY_RESULTS]);
        $this->listingCache->set($cacheKey, $reviews);

        return $reviews;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ReviewInterface>
     */
    public function getByShop(?int $minCreated = null, ?int $maxCreated = null, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%s:%d:%d', $minCreated ?? '', $maxCreated ?? '', $limit, $offset);
        if (!$skipCache) {
            if ($this->shopCache->has($cacheKey)) {
                /**
                 * @var array<int, ReviewInterface> $cached
                 */
                $cached = $this->shopCache->get($cacheKey);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_BY_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, self::buildQuery($minCreated, $maxCreated, $limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $reviews = $this->reviewsTransformer->transform($data[self::KEY_RESULTS]);
        $this->shopCache->set($cacheKey, $reviews);

        return $reviews;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(?int $minCreated, ?int $maxCreated, int $limit, int $offset): array
    {
        $query = [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
        if (null !== $minCreated) {
            $query[self::KEY_MIN_CREATED] = (string) $minCreated;
        }
        if (null !== $maxCreated) {
            $query[self::KEY_MAX_CREATED] = (string) $maxCreated;
        }

        return $query;
    }
}
