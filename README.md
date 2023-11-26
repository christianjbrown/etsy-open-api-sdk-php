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
use ChristianBrown\Etsy\Endpoint\ReceiptsApi;
use ChristianBrown\Etsy\Endpoint\ResultSetBasedApi;
use ChristianBrown\Etsy\Model\ReceiptInterface;
use ChristianBrown\Etsy\Model\TransactionInterface;
use ChristianBrown\Etsy\Request\ApiConnector;
use ChristianBrown\Etsy\Request\ApiConnectorInterface;
use ChristianBrown\Etsy\Request\AuthenticationManager;
use ChristianBrown\Etsy\Request\AuthenticationManagerInterface;
use ChristianBrown\Etsy\Transformer\BadResponseTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\ResultSetTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\JsonApiClient\RequestSender;
use ChristianBrown\KeyValueStore\DatabaseKeyValueStore;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;
use ChristianBrown\Oauth2Client\RefreshTokenManager;

$shopId = getenv('ETSY_SHOP_ID');

// Replace with your own token store, see christianbrown/key-value-store for examples.
$refreshTokenStore = new DatabaseKeyValueStore($entityManager, RefreshTokens::class, 'etsy-refresh-token');

$accessTokenStore = new MemoryKeyValueStore();
$refreshTokenManager = new RefreshTokenManager(AuthenticationManagerInterface::URL_OAUTH_TOKEN_API, ApiConnectorInterface::FRIENDLY_NAME, $refreshTokenStore, $accessTokenStore);
$authenticationManager = new AuthenticationManager($refreshTokenManager, $config->getKeyString());

$badResponseTransformer = new BadResponseTransformer();
$requestSender = new RequestSender($badResponseTransformer);

$apiConnector = new ApiConnector($authenticationManager, $requestSender);
$resultSetTransformer = new ResultSetTransformer();
$resultBasedApi = new ResultSetBasedApi($apiConnector, $resultSetTransformer, $shopId);

$transactionTransformer = new TransactionTransformer();
$transactionsTransformer = new TransactionsTransformer($transactionTransformer);
$receiptTransformer = new ReceiptTransformer($transactionsTransformer);
$receiptsTransformer = new ReceiptsTransformer($receiptTransformer);
$receiptsApi = new ReceiptsApi($resultBasedApi, $receiptsTransformer);
$resultSet = $receiptsApi->getResultSet();
$receipts = $resultSet->getResults();
```