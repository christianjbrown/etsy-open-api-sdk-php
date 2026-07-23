<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;

interface ShippingProfileApiInterface extends ApiInterface
{
    public const string API_URL_CARRIERS = 'https://openapi.etsy.com/v3/application/shipping-carriers';
    public const string API_URL_DESTINATIONS_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/destinations';
    public const string API_URL_MULTIPLE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles';
    public const string API_URL_ONE_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d';
    public const string API_URL_UPGRADES_SPRINTF = 'https://openapi.etsy.com/v3/application/shops/%d/shipping-profiles/%d/upgrades';
    public const string KEY_LIMIT = 'limit';
    public const string KEY_OFFSET = 'offset';
    public const string KEY_ORIGIN_COUNTRY_ISO = 'origin_country_iso';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE = 'Response not set or not an array';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

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
}
