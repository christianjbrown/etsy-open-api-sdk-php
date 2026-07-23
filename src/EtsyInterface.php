<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy;

use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Api\ShopApiInterface;
use ChristianBrown\Etsy\Api\ShopReceiptApiInterface;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Api\UserApiInterface;

interface EtsyInterface
{
    public const string OAUTH_TOKEN_URL = 'https://api.etsy.com/v3/public/oauth/token';
    public const string SERVICE_ACCESS_TOKEN_TRANSFORMER = 'etsy.oauth.access_token_transformer';
    public const string SERVICE_API_CLIENT = 'etsy.api_client';
    public const string SERVICE_CREDENTIALS = 'etsy.auth.credentials';
    public const string SERVICE_JSON_API_REQUEST_SENDER = 'etsy.json_api_request_sender';
    public const string SERVICE_LISTING_PROPERTY_VALUE_TRANSFORMER = 'etsy.transformer.listing_property_value_transformer';
    public const string SERVICE_LISTING_PROPERTY_VALUES_TRANSFORMER = 'etsy.transformer.listing_property_values_transformer';
    public const string SERVICE_MONEY_TRANSFORMER = 'etsy.transformer.money_transformer';
    public const string SERVICE_PING_API = 'etsy.api.ping_api';
    public const string SERVICE_PING_TRANSFORMER = 'etsy.transformer.ping_transformer';
    public const string SERVICE_RECEIPT_TRANSFORMER = 'etsy.transformer.receipt_transformer';
    public const string SERVICE_RECEIPTS_TRANSFORMER = 'etsy.transformer.receipts_transformer';
    public const string SERVICE_REFRESH_TOKEN_MANAGER = 'etsy.oauth.refresh_token_manager';
    public const string SERVICE_REFUND_TRANSFORMER = 'etsy.transformer.refund_transformer';
    public const string SERVICE_REFUNDS_TRANSFORMER = 'etsy.transformer.refunds_transformer';
    public const string SERVICE_SHIPMENT_TRANSFORMER = 'etsy.transformer.shipment_transformer';
    public const string SERVICE_SHIPMENTS_TRANSFORMER = 'etsy.transformer.shipments_transformer';
    public const string SERVICE_SHOP_API = 'etsy.api.shop_api';
    public const string SERVICE_SHOP_RECEIPT_API = 'etsy.api.shop_receipt_api';
    public const string SERVICE_SHOP_TRANSFORMER = 'etsy.transformer.shop_transformer';
    public const string SERVICE_SHOPS_TRANSFORMER = 'etsy.transformer.shops_transformer';
    public const string SERVICE_TRANSACTION_TRANSFORMER = 'etsy.transformer.transaction_transformer';
    public const string SERVICE_TRANSACTION_VARIATION_TRANSFORMER = 'etsy.transformer.transaction_variation_transformer';
    public const string SERVICE_TRANSACTION_VARIATIONS_TRANSFORMER = 'etsy.transformer.transaction_variations_transformer';
    public const string SERVICE_TRANSACTIONS_TRANSFORMER = 'etsy.transformer.transactions_transformer';
    public const string SERVICE_USER_ADDRESS_API = 'etsy.api.user_address_api';
    public const string SERVICE_USER_ADDRESS_TRANSFORMER = 'etsy.transformer.user_address_transformer';
    public const string SERVICE_USER_ADDRESSES_TRANSFORMER = 'etsy.transformer.user_addresses_transformer';
    public const string SERVICE_USER_API = 'etsy.api.user_api';
    public const string SERVICE_USER_TRANSFORMER = 'etsy.transformer.user_transformer';

    public function getPingApi(): PingApiInterface;

    public function getShopApi(): ShopApiInterface;

    public function getShopReceiptApi(): ShopReceiptApiInterface;

    public function getUserAddressApi(): UserAddressApiInterface;

    public function getUserApi(): UserApiInterface;
}
