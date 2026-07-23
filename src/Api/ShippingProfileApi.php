<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;

use function is_array;
use function sprintf;

final class ShippingProfileApi implements ShippingProfileApiInterface
{
    /**
     * @var array<string, array<int, ShippingCarrierInterface>>
     */
    private array $carriersCache = [];
    private CredentialsInterface $credentials;

    /**
     * @var array<string, array<int, ShopShippingProfileDestinationInterface>>
     */
    private array $destinationsCache = [];

    /**
     * @var array<int, ShopShippingProfileInterface>
     */
    private array $profileCache = [];

    /**
     * @var null|array<int, ShopShippingProfileInterface>
     */
    private ?array $profilesCache = null;
    private JsonApiRequestSenderInterface $requestSender;
    private ShippingCarriersTransformerInterface $shippingCarriersTransformer;
    private int $shopId;
    private ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer;
    private ShopShippingProfilesTransformerInterface $shopShippingProfilesTransformer;
    private ShopShippingProfileTransformerInterface $shopShippingProfileTransformer;
    private ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer;

    /**
     * @var array<int, array<int, ShopShippingProfileUpgradeInterface>>
     */
    private array $upgradesCache = [];

    public function __construct(JsonApiRequestSenderInterface $requestSender, ShopShippingProfileTransformerInterface $shopShippingProfileTransformer, ShopShippingProfilesTransformerInterface $shopShippingProfilesTransformer, ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer, ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer, ShippingCarriersTransformerInterface $shippingCarriersTransformer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->shopShippingProfileTransformer = $shopShippingProfileTransformer;
        $this->shopShippingProfilesTransformer = $shopShippingProfilesTransformer;
        $this->shopShippingProfileDestinationsTransformer = $shopShippingProfileDestinationsTransformer;
        $this->shopShippingProfileUpgradesTransformer = $shopShippingProfileUpgradesTransformer;
        $this->shippingCarriersTransformer = $shippingCarriersTransformer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShippingCarrierInterface>
     */
    public function getCarriers(string $originCountryIso, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (isset($this->carriersCache[$originCountryIso])) {
                return $this->carriersCache[$originCountryIso];
            }
        }

        $data = $this->requestSender->get(self::API_URL_CARRIERS, self::buildCarriersQuery($originCountryIso), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $shippingCarriers = $this->shippingCarriersTransformer->transform($data[self::KEY_RESULTS]);
        $this->carriersCache[$originCountryIso] = $shippingCarriers;

        return $shippingCarriers;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function getDestinations(int $shippingProfileId, int $limit = 25, int $offset = 0, bool $skipCache = false): array
    {
        $cacheKey = sprintf('%d:%d:%d', $shippingProfileId, $limit, $offset);
        if (!$skipCache) {
            if (isset($this->destinationsCache[$cacheKey])) {
                return $this->destinationsCache[$cacheKey];
            }
        }

        $url = sprintf(self::API_URL_DESTINATIONS_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->get($url, self::buildDestinationsQuery($limit, $offset), $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $destinations = $this->shopShippingProfileDestinationsTransformer->transform($data[self::KEY_RESULTS]);
        $this->destinationsCache[$cacheKey] = $destinations;

        return $destinations;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopShippingProfileInterface>
     */
    public function getMultiple(bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (null !== $this->profilesCache) {
                return $this->profilesCache;
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
        $shippingProfiles = $this->shopShippingProfilesTransformer->transform($data[self::KEY_RESULTS]);
        $this->profilesCache = $shippingProfiles;

        return $shippingProfiles;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $shippingProfileId, bool $skipCache = false): ShopShippingProfileInterface
    {
        if (!$skipCache) {
            if (isset($this->profileCache[$shippingProfileId])) {
                return $this->profileCache[$shippingProfileId];
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shippingProfile = $this->shopShippingProfileTransformer->transform($data);
        $this->profileCache[$shippingProfileId] = $shippingProfile;

        return $shippingProfile;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     *
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function getUpgrades(int $shippingProfileId, bool $skipCache = false): array
    {
        if (!$skipCache) {
            if (isset($this->upgradesCache[$shippingProfileId])) {
                return $this->upgradesCache[$shippingProfileId];
            }
        }

        $url = sprintf(self::API_URL_UPGRADES_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        if (!is_array($data[self::KEY_RESULTS])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_RESPONSE_SPRINTF, self::KEY_RESULTS));
        }
        $upgrades = $this->shopShippingProfileUpgradesTransformer->transform($data[self::KEY_RESULTS]);
        $this->upgradesCache[$shippingProfileId] = $upgrades;

        return $upgrades;
    }

    /**
     * @return array<string, string>
     */
    private static function buildCarriersQuery(string $originCountryIso): array
    {
        return [
            self::KEY_ORIGIN_COUNTRY_ISO => $originCountryIso,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function buildDestinationsQuery(int $limit, int $offset): array
    {
        return [
            self::KEY_LIMIT => (string) $limit,
            self::KEY_OFFSET => (string) $offset,
        ];
    }
}
