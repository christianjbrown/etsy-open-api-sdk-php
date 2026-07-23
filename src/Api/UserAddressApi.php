<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddressInterface;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformerInterface;
use ChristianBrown\Etsy\Transformer\UserAddressTransformerInterface;

use function is_array;
use function sprintf;

final class UserAddressApi implements UserAddressApiInterface
{
    /**
     * @var array<string, array<int, UserAddressInterface>>
     */
    private array $cache = [];
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;

    /**
     * @var array<int, UserAddressInterface>
     */
    private array $userAddressCache = [];
    private UserAddressesTransformerInterface $userAddressesTransformer;
    private UserAddressTransformerInterface $userAddressTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, UserAddressTransformerInterface $userAddressTransformer, UserAddressesTransformerInterface $userAddressesTransformer, CredentialsInterface $credentials)
    {
        $this->requestSender = $requestSender;
        $this->userAddressTransformer = $userAddressTransformer;
        $this->userAddressesTransformer = $userAddressesTransformer;
        $this->credentials = $credentials;
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
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
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
        $this->cache[$cacheKey] = $userAddresses;

        return $userAddresses;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $userAddressId, bool $skipCache = false): UserAddressInterface
    {
        if (!$skipCache) {
            if (isset($this->userAddressCache[$userAddressId])) {
                return $this->userAddressCache[$userAddressId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $userAddressId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $userAddress = $this->userAddressTransformer->transform($data);
        $this->userAddressCache[$userAddressId] = $userAddress;

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
