<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSectionInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopSectionApi implements ShopSectionApiInterface
{
    /**
     * @var null|array<int, ShopSectionInterface>
     */
    private ?array $cache = null;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;

    /**
     * @var array<int, ShopSectionInterface>
     */
    private array $shopSectionCache = [];
    private ShopSectionsTransformerInterface $shopSectionsTransformer;
    private ShopSectionTransformerInterface $shopSectionTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopSectionTransformerInterface $shopSectionTransformer, ShopSectionsTransformerInterface $shopSectionsTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopSectionTransformer = $shopSectionTransformer;
        $this->shopSectionsTransformer = $shopSectionsTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopSectionInterface>
     */
    public function getMultiple(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (null !== $this->cache) {
                return $this->cache;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $shopSections = $this->shopSectionsTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache = $shopSections;

        return $shopSections;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $shopSectionId, bool $skipCache = false): ShopSectionInterface
    {
        if (!$skipCache) {
            if (isset($this->shopSectionCache[$shopSectionId])) {
                return $this->shopSectionCache[$shopSectionId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shopSectionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopSection = $this->shopSectionTransformer->transform($data);
        $this->shopSectionCache[$shopSectionId] = $shopSection;

        return $shopSection;
    }
}
