<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformerInterface;

use function sprintf;

final class ShopHolidayPreferenceApi implements ShopHolidayPreferenceApiInterface
{
    /**
     * @var null|array<int, ShopHolidayPreferenceInterface>
     */
    private ?array $cache = null;
    private CredentialsInterface $credentials;
    private JsonApiRequestSenderInterface $requestSender;
    private ShopHolidayPreferencesTransformerInterface $shopHolidayPreferencesTransformer;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopHolidayPreferencesTransformerInterface $shopHolidayPreferencesTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopHolidayPreferencesTransformer = $shopHolidayPreferencesTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopHolidayPreferenceInterface>
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

        $shopHolidayPreferences = $this->shopHolidayPreferencesTransformer->transform($data);
        $this->cache = $shopHolidayPreferences;

        return $shopHolidayPreferences;
    }
}
