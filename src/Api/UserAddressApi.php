<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonReadApiRequestSenderInterface;
use ChristianBrown\ApiClient\ReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddressInterface;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformerInterface;
use ChristianBrown\Etsy\Transformer\UserAddressTransformerInterface;

use function is_array;
use function sprintf;

final class UserAddressApi implements UserAddressApiInterface
{
    private ReadApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private JsonReadApiRequestSenderInterface $requestSender;
    private ResponseCacheInterface $userAddressCache;
    private UserAddressesTransformerInterface $userAddressesTransformer;
    private UserAddressTransformerInterface $userAddressTransformer;

    public function __construct(JsonReadApiRequestSenderInterface $requestSender, ReadApiRequestSenderInterface $apiRequestSender, UserAddressTransformerInterface $userAddressTransformer, UserAddressesTransformerInterface $userAddressesTransformer, ResponseCacheInterface $cache, ResponseCacheInterface $userAddressCache, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->userAddressTransformer = $userAddressTransformer;
        $this->userAddressesTransformer = $userAddressesTransformer;
        $this->cache = $cache;
        $this->userAddressCache = $userAddressCache;
        $this->credentials = $credentials;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $userAddressId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $userAddressId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->clear();
        $this->userAddressCache->delete((string) $userAddressId);
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, UserAddressInterface>
     */
    public function getMultiple(int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d', $limit, $offset);
        if (!$skipCache) {
            if ($this->cache->has($cacheKey)) {
                /**
                 * @var array<int, UserAddressInterface> $cached
                 */
                $cached = $this->cache->get($cacheKey);

                return $cached;
            }
        }

        $data = $this->requestSender->get(self::API_URL_MULTIPLE, self::buildQuery($limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $userAddresses = $this->userAddressesTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set($cacheKey, $userAddresses);

        return $userAddresses;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $userAddressId, bool $skipCache = false): UserAddressInterface
    {
        if (!$skipCache) {
            if ($this->userAddressCache->has((string) $userAddressId)) {
                /**
                 * @var UserAddressInterface $cached
                 */
                $cached = $this->userAddressCache->get((string) $userAddressId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $userAddressId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $userAddress = $this->userAddressTransformer->transform($data);
        $this->userAddressCache->set((string) $userAddressId, $userAddress);

        return $userAddress;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(int $limit, int $offset): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }
}
