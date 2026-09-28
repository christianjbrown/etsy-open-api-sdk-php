<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferenceTransformerInterface;

use function sprintf;

final class ShopHolidayPreferenceApi implements ShopHolidayPreferenceApiInterface
{
    private ResponseCacheInterface $cache;
    private CredentialsInterface $credentials;
    private FormValueEncoderInterface $formValueEncoder;
    private JsonApiRequestSenderInterface $requestSender;
    private ShopHolidayPreferencesTransformerInterface $shopHolidayPreferencesTransformer;
    private ShopHolidayPreferenceTransformerInterface $shopHolidayPreferenceTransformer;
    private int $shopId;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopHolidayPreferenceTransformerInterface $shopHolidayPreferenceTransformer, ShopHolidayPreferencesTransformerInterface $shopHolidayPreferencesTransformer, FormValueEncoderInterface $formValueEncoder, ResponseCacheInterface $cache, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopHolidayPreferenceTransformer = $shopHolidayPreferenceTransformer;
        $this->shopHolidayPreferencesTransformer = $shopHolidayPreferencesTransformer;
        $this->formValueEncoder = $formValueEncoder;
        $this->cache = $cache;
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
            if ($this->cache->has('all')) {
                /**
                 * @var array<int, ShopHolidayPreferenceInterface> $cached
                 */
                $cached = $this->cache->get('all');

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        $shopHolidayPreferences = $this->shopHolidayPreferencesTransformer->transform($data);
        $this->cache->set('all', $shopHolidayPreferences);

        return $shopHolidayPreferences;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function updateHolidayPreference(int $holidayId, bool $isWorking): ShopHolidayPreferenceInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $holidayId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), [self::KEY_IS_WORKING => $this->formValueEncoder->encodeBool($isWorking)]);

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopHolidayPreference = $this->shopHolidayPreferenceTransformer->transform($data);
        $this->cache->clear();

        return $shopHolidayPreference;
    }
}
