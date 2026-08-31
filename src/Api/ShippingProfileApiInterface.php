<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

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

interface ShippingProfileApiInterface extends ApiInterface
{
    public const string API_URL_CARRIERS = 'https://openapi.etsy.com/v3/application/shipping-carriers';
    public const string API_URL_DESTINATION_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/destinations/%d';
    public const string API_URL_DESTINATIONS_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/destinations';
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d';
    public const string API_URL_UPGRADE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/upgrades/%d';
    public const string API_URL_UPGRADES_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/upgrades';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Creates a new shipping profile for the shop.
     */
    public function create(CreateShopShippingProfileRequestInterface $createShopShippingProfileRequest): ShopShippingProfileInterface;

    /**
     * Adds a destination to a shipping profile.
     */
    public function createDestination(int $shippingProfileId, CreateShopShippingProfileDestinationRequestInterface $createShopShippingProfileDestinationRequest): ShopShippingProfileDestinationInterface;

    /**
     * Adds an upgrade (e.g. expedited shipping) to a shipping profile.
     */
    public function createUpgrade(int $shippingProfileId, CreateShopShippingProfileUpgradeRequestInterface $createShopShippingProfileUpgradeRequest): ShopShippingProfileUpgradeInterface;

    /**
     * Deletes a shipping profile from the shop.
     */
    public function delete(int $shippingProfileId): void;

    /**
     * Removes a destination from a shipping profile.
     */
    public function deleteDestination(int $shippingProfileId, int $shippingProfileDestinationId): void;

    /**
     * Removes an upgrade from a shipping profile.
     */
    public function deleteUpgrade(int $shippingProfileId, int $upgradeId): void;

    /**
     * Reads the list of supported shipping carriers and their mail classes for a country.
     *
     * @return array<int, ShippingCarrierInterface>
     */
    public function getCarriers(string $originCountryIso, bool $skipCache = false): array;

    /**
     * Reads a single page of the shipping profile's destinations.
     *
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function getDestinations(int $shippingProfileId, int $limit = 25, int $offset = 0, bool $skipCache = false): array;

    /**
     * Reads all the shipping profiles in the shop.
     *
     * @return array<int, ShopShippingProfileInterface>
     */
    public function getMultiple(bool $skipCache = false): array;

    public function getOneById(int $shippingProfileId, bool $skipCache = false): ShopShippingProfileInterface;

    /**
     * Reads all the shipping upgrades for a shipping profile.
     *
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function getUpgrades(int $shippingProfileId, bool $skipCache = false): array;

    /**
     * Updates a shipping profile's title, origin or processing time.
     */
    public function update(int $shippingProfileId, UpdateShopShippingProfileRequestInterface $updateShopShippingProfileRequest): ShopShippingProfileInterface;

    /**
     * Updates a destination on a shipping profile.
     */
    public function updateDestination(int $shippingProfileId, int $shippingProfileDestinationId, UpdateShopShippingProfileDestinationRequestInterface $updateShopShippingProfileDestinationRequest): ShopShippingProfileDestinationInterface;

    /**
     * Updates an upgrade on a shipping profile.
     */
    public function updateUpgrade(int $shippingProfileId, int $upgradeId, UpdateShopShippingProfileUpgradeRequestInterface $updateShopShippingProfileUpgradeRequest): ShopShippingProfileUpgradeInterface;
}
