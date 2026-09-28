<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformerInterface;

use function is_array;
use function sprintf;

final class ShopProductionPartnerApi implements ShopProductionPartnerApiInterface
{
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private int $shopId;
    private ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopProductionPartnersTransformer = $shopProductionPartnersTransformer;
        $this->cache = $cache;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopProductionPartnerInterface>
     */
    public function getMultiple(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if ($this->cache->has('all')) {
                /**
                 * @var array<int, ShopProductionPartnerInterface> $cached
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
        $shopProductionPartners = $this->shopProductionPartnersTransformer->transform($data[self::KEY_RESULTS]);
        $this->cache->set('all', $shopProductionPartners);

        return $shopProductionPartners;
    }
}
