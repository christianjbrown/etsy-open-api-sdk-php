# eBay Shopping API

This is a simple PHP library for Etsy's APIs.

## Prerequisites

You will need [PHP](https://www.php.net/) 8.2 (or higher up to 9.0) and [Composer](https://getcomposer.org/).

## Installation

```bash
composer require christianjbrown/etsy-api
```

## Usage

```php
use ChristianBrown\Etsy\Api;
use ChristianBrown\KeyValueStore\DatabaseKeyValueStore;

$shopId = getenv('ETSY_SHOP_ID');
$keyString = getenv('ETSY_KEY_STRING');

// Replace with your own key-value token value store, see christianbrown/key-value-store for examples.
// You will need to generate the first refresh token manually, and then store it in the key-value store.
$refreshTokenStore = new DatabaseKeyValueStore($entityManager, RefreshTokens::class, 'etsy-refresh-token');

$api = new Api($shopId, $keyString, $refreshTokenStore);
$receiptsApi = $api->getReceiptsApi();

$resultSet = $receiptsApi->getResultSet();
$receipts = $resultSet->getResults();
```