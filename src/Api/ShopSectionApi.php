<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSectionInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopSectionTransformerInterface;

use function is_array;
use function sprintf;

final class ShopSectionApi implements ShopSectionApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private ResponseCacheInterface $shopSectionCache;
    private ShopSectionsTransformerInterface $shopSectionsTransformer;
    private ShopSectionTransformerInterface $shopSectionTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ShopSectionTransformerInterface $shopSectionTransformer, ShopSectionsTransformerInterface $shopSectionsTransformer, ResponseCacheInterface $shopSectionCache, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->shopSectionTransformer = $shopSectionTransformer;
        $this->shopSectionsTransformer = $shopSectionsTransformer;
        $this->shopSectionCache = $shopSectionCache;
        $this->cache = $cache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(string $title): ShopSectionInterface
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), [self::KEY_TITLE => $title]);

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopSection = $this->shopSectionTransformer->transform($data);
        $this->cache->clear();

        return $shopSection;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $shopSectionId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shopSectionId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->cache->clear();
        $this->shopSectionCache->delete((string) $shopSectionId);
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
            if ($this->cache->has('all')) {
                /**
                 * @var array<int, ShopSectionInterface> $cached
                 */
                $cached = $this->cache->get('all');

                return $cached;
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
        $this->cache->set('all', $shopSections);

        return $shopSections;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $shopSectionId, bool $skipCache = false): ShopSectionInterface
    {
        if (!$skipCache) {
            if ($this->shopSectionCache->has((string) $shopSectionId)) {
                /**
                 * @var ShopSectionInterface $cached
                 */
                $cached = $this->shopSectionCache->get((string) $shopSectionId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shopSectionId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopSection = $this->shopSectionTransformer->transform($data);
        $this->shopSectionCache->set((string) $shopSectionId, $shopSection);

        return $shopSection;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $shopSectionId, string $title): ShopSectionInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shopSectionId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), [self::KEY_TITLE => $title]);

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopSection = $this->shopSectionTransformer->transform($data);
        $this->cache->clear();
        $this->shopSectionCache->set((string) $shopSectionId, $shopSection);

        return $shopSection;
    }
}
