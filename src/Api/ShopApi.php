<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopInterface;
use ChristianBrown\Etsy\Transformer\ShopsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopTransformerInterface;

use function is_array;
use function sprintf;

final class ShopApi implements ShopApiInterface
{
    private CredentialsInterface $credentials;

    /**
     * @var array<string, array<int, ShopInterface>>
     */
    private array $findCache = [];

    /**
     * @var array<int, ShopInterface>
     */
    private array $ownerCache = [];
    private JsonApiRequestSenderInterface $requestSender;
    private ?ShopInterface $shopCache = null;
    private int $shopId;
    private ShopsTransformerInterface $shopsTransformer;
    private ShopTransformerInterface $shopTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopTransformerInterface $shopTransformer, ShopsTransformerInterface $shopsTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopTransformer = $shopTransformer;
        $this->shopsTransformer = $shopsTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopInterface>
     */
    public function findByName(string $shopName, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%s:%d:%d', $shopName, $limit, $offset);
        if (!$skipCache) {
            if (isset($this->findCache[$cacheKey])) {
                return $this->findCache[$cacheKey];
            }
        }

        $data = $this->requestSender->get(self::API_URL_FIND, self::buildQuery($shopName, $limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $shops = $this->shopsTransformer->transform($data[self::KEY_RESULTS]);
        $this->findCache[$cacheKey] = $shops;

        return $shops;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getByOwnerUserId(int $userId, bool $skipCache = false): ShopInterface
    {
        if (!$skipCache) {
            if (isset($this->ownerCache[$userId])) {
                return $this->ownerCache[$userId];
            }
        }

        $url = sprintf(self::API_URL_BY_OWNER_SPRINTF, $userId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shop = $this->shopTransformer->transform($data);
        $this->ownerCache[$userId] = $shop;

        return $shop;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getShop(bool $skipCache = false): ShopInterface
    {
        if (!$skipCache) {
            if (null !== $this->shopCache) {
                return $this->shopCache;
            }
        }

        $url = sprintf(self::API_URL_SHOP_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shop = $this->shopTransformer->transform($data);
        $this->shopCache = $shop;

        return $shop;
    }

    /**
     * @return array<string, string>
     */
    private static function buildQuery(string $shopName, int $limit, int $offset): array
    {
        return [
            self::KEY_SHOP_NAME => $shopName,
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }
}
