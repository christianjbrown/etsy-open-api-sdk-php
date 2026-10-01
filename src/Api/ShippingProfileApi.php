<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\ApiClient\Exception\Request\RequestExceptionInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\ApiClient\ReadApiRequestSenderInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCacheInterface;
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
    private ReadApiRequestSenderInterface $apiRequestSender;
    private ResponseCacheInterface $carriersCache;
    private CreateShopShippingProfileDestinationRequestSerializerInterface $createShopShippingProfileDestinationRequestSerializer;
    private CreateShopShippingProfileRequestSerializerInterface $createShopShippingProfileRequestSerializer;
    private CreateShopShippingProfileUpgradeRequestSerializerInterface $createShopShippingProfileUpgradeRequestSerializer;
    private CredentialsInterface $credentials;
    private ResponseCacheInterface $destinationsCache;
    private ResponseCacheInterface $profileCache;
    private ResponseCacheInterface $profilesCache;
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
    private ResponseCacheInterface $upgradesCache;

    public function __construct(JsonApiRequestSenderInterface $requestSender, ReadApiRequestSenderInterface $apiRequestSender, ShopShippingProfileTransformerInterface $shopShippingProfileTransformer, ShopShippingProfilesTransformerInterface $shopShippingProfilesTransformer, ShopShippingProfileDestinationTransformerInterface $shopShippingProfileDestinationTransformer, ShopShippingProfileDestinationsTransformerInterface $shopShippingProfileDestinationsTransformer, ShopShippingProfileUpgradeTransformerInterface $shopShippingProfileUpgradeTransformer, ShopShippingProfileUpgradesTransformerInterface $shopShippingProfileUpgradesTransformer, ShippingCarriersTransformerInterface $shippingCarriersTransformer, CreateShopShippingProfileRequestSerializerInterface $createShopShippingProfileRequestSerializer, CreateShopShippingProfileDestinationRequestSerializerInterface $createShopShippingProfileDestinationRequestSerializer, CreateShopShippingProfileUpgradeRequestSerializerInterface $createShopShippingProfileUpgradeRequestSerializer, UpdateShopShippingProfileRequestSerializerInterface $updateShopShippingProfileRequestSerializer, UpdateShopShippingProfileDestinationRequestSerializerInterface $updateShopShippingProfileDestinationRequestSerializer, UpdateShopShippingProfileUpgradeRequestSerializerInterface $updateShopShippingProfileUpgradeRequestSerializer, ResponseCacheInterface $carriersCache, ResponseCacheInterface $destinationsCache, ResponseCacheInterface $profileCache, ResponseCacheInterface $upgradesCache, ResponseCacheInterface $profilesCache, CredentialsInterface $credentials, int $shopId)
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
        $this->carriersCache = $carriersCache;
        $this->destinationsCache = $destinationsCache;
        $this->profileCache = $profileCache;
        $this->upgradesCache = $upgradesCache;
        $this->profilesCache = $profilesCache;
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
        $this->profilesCache->clear();

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
        $this->destinationsCache->clear();

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
        $this->upgradesCache->delete((string) $shippingProfileId);

        return $upgrade;
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function delete(int $shippingProfileId): void
    {
        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shippingProfileId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->profilesCache->clear();
        $this->profileCache->delete((string) $shippingProfileId);
        $this->destinationsCache->clear();
        $this->upgradesCache->delete((string) $shippingProfileId);
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function deleteDestination(int $shippingProfileId, int $shippingProfileDestinationId): void
    {
        $url = sprintf(self::API_URL_DESTINATION_SPRINTF, $this->shopId, $shippingProfileId, $shippingProfileDestinationId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->destinationsCache->clear();
    }

    /**
     * @throws RequestExceptionInterface
     */
    public function deleteUpgrade(int $shippingProfileId, int $upgradeId): void
    {
        $url = sprintf(self::API_URL_UPGRADE_SPRINTF, $this->shopId, $shippingProfileId, $upgradeId);
        $this->apiRequestSender->delete($url, [], $this->credentials->toHeaders());

        $this->upgradesCache->delete((string) $shippingProfileId);
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
            if ($this->carriersCache->has($originCountryIso)) {
                /**
                 * @var array<int, ShippingCarrierInterface> $cached
                 */
                $cached = $this->carriersCache->get($originCountryIso);

                return $cached;
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
        $this->carriersCache->set($originCountryIso, $shippingCarriers);

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
            if ($this->destinationsCache->has($cacheKey)) {
                /**
                 * @var array<int, ShopShippingProfileDestinationInterface> $cached
                 */
                $cached = $this->destinationsCache->get($cacheKey);

                return $cached;
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
        $this->destinationsCache->set($cacheKey, $destinations);

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
            if ($this->profilesCache->has('all')) {
                /**
                 * @var array<int, ShopShippingProfileInterface> $cached
                 */
                $cached = $this->profilesCache->get('all');

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
        $shippingProfiles = $this->shopShippingProfilesTransformer->transform($data[self::KEY_RESULTS]);
        $this->profilesCache->set('all', $shippingProfiles);

        return $shippingProfiles;
    }

    /**
     * @throws RequestExceptionInterface
     * @throws UnexpectedResponseException
     */
    public function getOneById(int $shippingProfileId, bool $skipCache = false): ShopShippingProfileInterface
    {
        if (!$skipCache) {
            if ($this->profileCache->has((string) $shippingProfileId)) {
                /**
                 * @var ShopShippingProfileInterface $cached
                 */
                $cached = $this->profileCache->get((string) $shippingProfileId);

                return $cached;
            }
        }

        $url = sprintf(self::API_URL_ONE_SPRINTF, $this->shopId, $shippingProfileId);
        $data = $this->requestSender->get($url, [], $this->credentials->toHeaders());

        if (empty($data)) {
            throw new UnexpectedResponseException(self::UNEXPECTED_RESPONSE);
        }
        $shippingProfile = $this->shopShippingProfileTransformer->transform($data);
        $this->profileCache->set((string) $shippingProfileId, $shippingProfile);

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
            if ($this->upgradesCache->has((string) $shippingProfileId)) {
                /**
                 * @var array<int, ShopShippingProfileUpgradeInterface> $cached
                 */
                $cached = $this->upgradesCache->get((string) $shippingProfileId);

                return $cached;
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
        $this->upgradesCache->set((string) $shippingProfileId, $upgrades);

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
        $this->profilesCache->clear();
        $this->profileCache->set((string) $shippingProfileId, $shopShippingProfile);

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
        $this->destinationsCache->clear();

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
        $this->upgradesCache->delete((string) $shippingProfileId);

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
