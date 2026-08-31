<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileDestinationRequestInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileRequestInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileUpgradeRequestInterface;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileDestinationRequestInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileRequestInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileUpgradeRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileDestinationRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileUpgradeRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileDestinationRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileUpgradeRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformerInterface;

use function is_array;
use function sprintf;

final class ShippingProfileApi implements ShippingProfileApiInterface
{
    private ApiRequestSenderInterface $apiRequestSender;

    /**
     * @var array<string, array<int, ShippingCarrierInterface>>
     */
    private array $carriersCache = [];
    private CreateShopShippingProfileDestinationRequestSerializerInterface $createShopShippingProfileDestinationRequestSerializer;
    private CreateShopShippingProfileRequestSerializerInterface $createShopShippingProfileRequestSerializer;
    private CreateShopShippingProfileUpgradeRequestSerializerInterface $createShopShippingProfileUpgradeRequestSerializer;
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
    private ShopShippingProfileDestinationTransformerInterface $shopShippingProfileDestinationTransformer;
    private ShopShippingProfilesTransformerInterface $shopShippingProfilesTransformer;
    private ShopShippingProfileTransformerInterface $shopShippingProfileTransformer;
    private ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer;
    private ShopShippingProfileUpgradeTransformerInterface $shopShippingProfileUpgradeTransformer;
    private UpdateShopShippingProfileDestinationRequestSerializerInterface $updateShopShippingProfileDestinationRequestSerializer;
    private UpdateShopShippingProfileRequestSerializerInterface $updateShopShippingProfileRequestSerializer;
    private UpdateShopShippingProfileUpgradeRequestSerializerInterface $updateShopShippingProfileUpgradeRequestSerializer;

    /**
     * @var array<int, array<int, ShopShippingProfileUpgradeInterface>>
     */
    private array $upgradesCache = [];

    public function __construct(JsonApiRequestSenderInterface $requestSender, ApiRequestSenderInterface $apiRequestSender, ShopShippingProfileTransformerInterface $shopShippingProfileTransformer, ShopShippingProfilesTransformerInterface $shopShippingProfilesTransformer, ShopShippingProfileDestinationTransformerInterface $shopShippingProfileDestinationTransformer, ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer, ShopShippingProfileUpgradeTransformerInterface $shopShippingProfileUpgradeTransformer, ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer, ShippingCarriersTransformerInterface $shippingCarriersTransformer, CreateShopShippingProfileRequestSerializerInterface $createShopShippingProfileRequestSerializer, CreateShopShippingProfileDestinationRequestSerializerInterface $createShopShippingProfileDestinationRequestSerializer, CreateShopShippingProfileUpgradeRequestSerializerInterface $createShopShippingProfileUpgradeRequestSerializer, UpdateShopShippingProfileRequestSerializerInterface $updateShopShippingProfileRequestSerializer, UpdateShopShippingProfileDestinationRequestSerializerInterface $updateShopShippingProfileDestinationRequestSerializer, UpdateShopShippingProfileUpgradeRequestSerializerInterface $updateShopShippingProfileUpgradeRequestSerializer, CredentialsInterface $credentials, int $shopId)
    {
        $this->requestSender = $requestSender;
        $this->apiRequestSender = $apiRequestSender;
        $this->shopShippingProfileTransformer = $shopShippingProfileTransformer;
        $this->shopShippingProfilesTransformer = $shopShippingProfilesTransformer;
        $this->shopShippingProfileDestinationTransformer = $shopShippingProfileDestinationTransformer;
        $this->shopShippingProfileDestinationsTransformer = $shopShippingProfileDestinationsTransformer;
        $this->shopShippingProfileUpgradeTransformer = $shopShippingProfileUpgradeTransformer;
        $this->shopShippingProfileUpgradesTransformer = $shopShippingProfileUpgradesTransformer;
        $this->shippingCarriersTransformer = $shippingCarriersTransformer;
        $this->createShopShippingProfileRequestSerializer = $createShopShippingProfileRequestSerializer;
        $this->createShopShippingProfileDestinationRequestSerializer = $createShopShippingProfileDestinationRequestSerializer;
        $this->createShopShippingProfileUpgradeRequestSerializer = $createShopShippingProfileUpgradeRequestSerializer;
        $this->updateShopShippingProfileRequestSerializer = $updateShopShippingProfileRequestSerializer;
        $this->updateShopShippingProfileDestinationRequestSerializer = $updateShopShippingProfileDestinationRequestSerializer;
        $this->updateShopShippingProfileUpgradeRequestSerializer = $updateShopShippingProfileUpgradeRequestSerializer;
        $this->credentials = $credentials;
        $this->shopId = $shopId;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function create(CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): ShopShippingProfileInterface
    {
        $url = sprintf(self::API_URL_MULTIPLE_SPRINTF, $this->shopId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->createShopShippingProfileRequestSerializer->serialize($createShopShippingProfileRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopShippingProfile = $this->shopShippingProfileTransformer->transform($data);
        $this->profilesCache = null;

        return $shopShippingProfile;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function createDestination(int $shippingProfileId, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): ShopShippingProfileDestinationInterface
    {
        $url = sprintf(self::API_URL_DESTINATIONS_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->createShopShippingProfileDestinationRequestSerializer->serialize($createShopShippingProfileDestinationRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $destination = $this->shopShippingProfileDestinationTransformer->transform($data);
        $this->destinationsCache = [];

        return $destination;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function createUpgrade(int $shippingProfileId, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): ShopShippingProfileUpgradeInterface
    {
        $url = sprintf(self::API_URL_UPGRADES_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->postForm($url, [], $this->credentials->toHeaders(), $this->createShopShippingProfileUpgradeRequestSerializer->serialize($createShopShippingProfileUpgradeRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $upgrade = $this->shopShippingProfileUpgradeTransformer->transform($data);
        unset($this->upgradesCache[$shippingProfileId]);

        return $upgrade;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $shippingProfileId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shippingProfileId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->profilesCache = null;
        unset($this->profileCache[$shippingProfileId]);
        $this->destinationsCache = [];
        unset($this->upgradesCache[$shippingProfileId]);
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function deleteDestination(int $shippingProfileId, int $shippingProfileDestinationId): void
    {
        $url = sprintf(self::API_URL_DESTINATION_SPRINTF, $this->shopId, $shippingProfileId, $shippingProfileDestinationId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->destinationsCache = [];
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function deleteUpgrade(int $shippingProfileId, int $upgradeId): void
    {
        $url = sprintf(self::API_URL_UPGRADE_SPRINTF, $this->shopId, $shippingProfileId, $upgradeId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        unset($this->upgradesCache[$shippingProfileId]);
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
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function update(int $shippingProfileId, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): ShopShippingProfileInterface
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->updateShopShippingProfileRequestSerializer->serialize($updateShopShippingProfileRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shopShippingProfile = $this->shopShippingProfileTransformer->transform($data);
        $this->profilesCache = null;
        $this->profileCache[$shippingProfileId] = $shopShippingProfile;

        return $shopShippingProfile;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function updateDestination(int $shippingProfileId, int $shippingProfileDestinationId, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): ShopShippingProfileDestinationInterface
    {
        $url = sprintf(self::API_URL_DESTINATION_SPRINTF, $this->shopId, $shippingProfileId, $shippingProfileDestinationId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->updateShopShippingProfileDestinationRequestSerializer->serialize($updateShopShippingProfileDestinationRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $destination = $this->shopShippingProfileDestinationTransformer->transform($data);
        $this->destinationsCache = [];

        return $destination;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function updateUpgrade(int $shippingProfileId, int $upgradeId, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): ShopShippingProfileUpgradeInterface
    {
        $url = sprintf(self::API_URL_UPGRADE_SPRINTF, $this->shopId, $shippingProfileId, $upgradeId);
        $data = $this->requestSender->putForm($url, [], $this->credentials->toHeaders(), $this->updateShopShippingProfileUpgradeRequestSerializer->serialize($updateShopShippingProfileUpgradeRequest));

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $upgrade = $this->shopShippingProfileUpgradeTransformer->transform($data);
        unset($this->upgradesCache[$shippingProfileId]);

        return $upgrade;
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
