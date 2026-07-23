# Etsy Open API v3 Client

[![CI](https://github.com/christianjbrown/php-etsy-open-api-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/christianjbrown/php-etsy-open-api-sdk/actions/workflows/ci.yml)

A strongly-typed PHP client for the [Etsy Open API v3](https://developers.etsy.com/documentation/). It reads your shop's data — receipts, listings, transactions, and more — returning plain, typed model objects rather than raw arrays.

The client is **read-only** (it wraps the API's `GET` endpoints only; creating and updating data is not supported yet) and currently supports:

- **Reading shop receipts** — a page of the shop's receipts (`getMultiple`) or a single receipt by id (`getOneById`). Each receipt carries the full order: buyer and address fields, the money totals (grandtotal, subtotal, shipping, tax, VAT, discount, gift wrap), and its nested transactions, refunds, and shipments.

### Supported endpoints

| Resource | Client | Endpoint(s) | Returns |
| --- | --- | --- | --- |
| Shop receipts | `getShopReceiptApi()` | `GET /shops/{shop_id}/receipts`, `GET /shops/{shop_id}/receipts/{receipt_id}` | `ReceiptInterface[]` / `ReceiptInterface` |
| Shop receipt transactions | `getShopReceiptTransactionApi()` | `GET /shops/{shop_id}/transactions/{transaction_id}`, `GET /shops/{shop_id}/receipts/{receipt_id}/transactions`, `GET /shops/{shop_id}/listings/{listing_id}/transactions`, `GET /shops/{shop_id}/transactions` | `TransactionInterface` / `TransactionInterface[]` |
| Payments | `getPaymentApi()` | `GET /shops/{shop_id}/payments`, `GET /shops/{shop_id}/receipts/{receipt_id}/payments`, `GET /shops/{shop_id}/payment-account/ledger-entries/payments` | `PaymentInterface[]` |
| Ledger entries | `getLedgerEntryApi()` | `GET /shops/{shop_id}/payment-account/ledger-entries`, `GET /shops/{shop_id}/payment-account/ledger-entries/{ledger_entry_id}` | `PaymentAccountLedgerEntryInterface[]` / `PaymentAccountLedgerEntryInterface` |
| Listings | `getShopListingApi()` | `GET /listings/{listing_id}`, `GET /shops/{shop_id}/listings`, `GET /listings/active`, `GET /shops/{shop_id}/listings/active`, `GET /listings/batch`, `GET /shops/{shop_id}/listings/featured`, `GET /shops/{shop_id}/policies/return/{return_policy_id}/listings`, `GET /shops/{shop_id}/receipts/{receipt_id}/listings`, `GET /shops/{shop_id}/shop-sections/listings` | `ListingInterface` / `ListingInterface[]` |
| Listing files | `getListingFileApi()` | `GET /shops/{shop_id}/listings/{listing_id}/files`, `GET /shops/{shop_id}/listings/{listing_id}/files/{listing_file_id}` | `ListingFileInterface[]` / `ListingFileInterface` |
| Listing images | `getListingImageApi()` | `GET /listings/{listing_id}/images`, `GET /listings/{listing_id}/images/{listing_image_id}` | `ListingImageInterface[]` / `ListingImageInterface` |
| Listing videos | `getListingVideoApi()` | `GET /listings/{listing_id}/videos`, `GET /listings/{listing_id}/videos/{video_id}` | `ListingVideoInterface[]` / `ListingVideoInterface` |
| Listing variation images | `getListingVariationImageApi()` | `GET /shops/{shop_id}/listings/{listing_id}/variation-images` | `ListingVariationImageInterface[]` |
| Listing properties | `getListingPropertyApi()` | `GET /shops/{shop_id}/listings/{listing_id}/properties`, `GET /listings/{listing_id}/properties/{property_id}` | `ListingPropertyValueInterface[]` / `ListingPropertyValueInterface` |
| Listing inventory | `getListingInventoryApi()` | `GET /listings/{listing_id}/inventory`, `GET /listings/{listing_id}/inventory/products/{product_id}`, `GET /listings/{listing_id}/products/{product_id}/offerings/{product_offering_id}` | `ListingInventoryInterface` / `ListingInventoryProductInterface` / `ListingInventoryProductOfferingInterface` |
| Listing translations | `getListingTranslationApi()` | `GET /shops/{shop_id}/listings/{listing_id}/translations/{language}` | `ListingTranslationInterface` |
| Listing personalization | `getListingPersonalizationApi()` | `GET /listings/{listing_id}/personalization` | `ListingPersonalizationInterface` |
| Shops | `getShopApi()` | `GET /shops/{shop_id}`, `GET /users/{user_id}/shops`, `GET /shops?shop_name=…` | `ShopInterface` / `ShopInterface[]` |
| Shop sections | `getShopSectionApi()` | `GET /shops/{shop_id}/sections`, `GET /shops/{shop_id}/sections/{shop_section_id}` | `ShopSectionInterface[]` / `ShopSectionInterface` |
| Reviews | `getReviewApi()` | `GET /shops/{shop_id}/reviews`, `GET /listings/{listing_id}/reviews` | `ReviewInterface[]` |
| Shop return policies | `getShopReturnPolicyApi()` | `GET /shops/{shop_id}/policies/return`, `GET /shops/{shop_id}/policies/return/{return_policy_id}` | `ShopReturnPolicyInterface[]` / `ShopReturnPolicyInterface` |
| Shop production partners | `getShopProductionPartnerApi()` | `GET /shops/{shop_id}/production-partners` | `ShopProductionPartnerInterface[]` |
| Shop holiday preferences | `getShopHolidayPreferenceApi()` | `GET /shops/{shop_id}/holiday-preferences` | `ShopHolidayPreferenceInterface[]` |
| Shop readiness state definitions | `getShopReadinessStateDefinitionApi()` | `GET /shops/{shop_id}/readiness-state-definitions`, `GET /shops/{shop_id}/readiness-state-definitions/{readiness_state_definition_id}` | `ShopReadinessStateDefinitionInterface[]` / `ShopReadinessStateDefinitionInterface` |
| Shipping profiles | `getShippingProfileApi()` | `GET /shops/{shop_id}/shipping-profiles`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}/destinations`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}/upgrades`, `GET /shipping-carriers?origin_country_iso=…` | `ShopShippingProfileInterface[]` / `ShopShippingProfileInterface` / `ShopShippingProfileDestinationInterface[]` / `ShopShippingProfileUpgradeInterface[]` / `ShippingCarrierInterface[]` |
| Users | `getUserApi()` | `GET /users/{user_id}`, `GET /users/me` | `UserInterface` |
| User addresses | `getUserAddressApi()` | `GET /user/addresses`, `GET /user/addresses/{user_address_id}` | `UserAddressInterface[]` / `UserAddressInterface` |
| Ping | `getPingApi()` | `GET /openapi-ping` | `PingInterface` |

_This table grows as more of the read API is covered._

## :heavy_check_mark: Prerequisites

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.5 or higher (8.x)
- [Composer](https://getcomposer.org/)

:bulb: If you're on MacOS and have [Homebrew](https://brew.sh/), PHP and Composer will install with `brew install composer`.

## :building_construction: Installation

For your composer-enabled project:

```bash
composer require christianjbrown/php-etsy-open-api-sdk
```

## :computer: Usage

Etsy's Open API v3 authenticates every request with two pieces: your app's **keystring** (sent as the `x-api-key` header) and an **OAuth 2.0 access token** (sent as `Authorization: Bearer …`). Access tokens are short-lived, so this client refreshes them for you using a long-lived **refresh token** and the OAuth2 `refresh_token` grant.

You supply four things to the `Etsy` entry point:

- your numeric **shop id**,
- your app **keystring** (which Etsy also uses as the OAuth `client_id`),
- a **`KeyValueStoreInterface`** to hold the current access token (an in-memory store is fine — it's re-fetched as needed),
- a **`KeyValueStoreInterface`** holding your refresh token. This one must **persist** (a database, secret store, etc.), because Etsy rotates the refresh token on every refresh and the client writes the new value back. Seed it once with a refresh token obtained from Etsy's [OAuth authorization flow](https://developers.etsy.com/documentation/essentials/authentication).

```php
use ChristianBrown\Etsy\Etsy;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;

// Access token: transient, an in-memory store is fine.
$accessTokenStore = new MemoryKeyValueStore();

// Refresh token: must persist and already hold a valid refresh token.
// Any KeyValueStoreInterface works (DatabaseKeyValueStore, FirestoreKeyValueStore, …).
$refreshTokenStore = new MemoryKeyValueStore();
$refreshTokenStore->setValue('your-seed-refresh-token');

$etsy = new Etsy(
    12345678,                 // your shop id
    'your-app-keystring',     // x-api-key + OAuth client_id
    $accessTokenStore,
    $refreshTokenStore
);

$shopReceiptApi = $etsy->getShopReceiptApi();  // ShopReceiptApiInterface
```

Reading receipts then looks like this:

```php
$receipts = $shopReceiptApi->getMultiple(limit: 25, offset: 0);   // ReceiptInterface[]
foreach ($receipts as $receipt) {
    printf("Receipt #%d — %s\n", $receipt->getReceiptId(), $receipt->getName() ?? 'unknown buyer');

    $grandtotal = $receipt->getGrandtotal();
    if ($grandtotal !== null) {
        printf("  Total: %d %s (÷%d)\n", $grandtotal->getAmount(), $grandtotal->getCurrencyCode(), $grandtotal->getDivisor());
    }

    foreach ($receipt->getTransactions() as $transaction) {
        printf("  %d × listing %d\n", $transaction->getQuantity() ?? 0, $transaction->getListingId() ?? 0);
    }
}

$receipt = $shopReceiptApi->getOneById(1234567890);   // ReceiptInterface
echo $receipt->getStatus() ?? 'unknown', "\n";
```

## :rotating_light: Error handling

Everything this library throws implements `ChristianBrown\Etsy\Exception\ExceptionInterface`, so a single `catch` covers it all:

```php
use ChristianBrown\Etsy\Exception\ExceptionInterface;

try {
    $receipts = $shopReceiptApi->getMultiple();
} catch (ExceptionInterface $exception) {
    // Anything this library throws lands here.
}
```

There are two concrete types:

- **`UnexpectedResponseException`** (extends `RuntimeException`) — the Etsy API returned a body the client or a transformer couldn't parse (a missing/mis-typed field, an empty response).
- **`MissingInputException`** (extends `InvalidArgumentException`) — bad caller input.

Both live in `src/Exception/`. Request-level failures (network errors, non-2xx responses) surface as `RequestExceptionInterface` from [`christianjbrown/php-api-client-lib`](https://github.com/christianjbrown/php-api-client-lib); token-refresh failures surface as `RequestExceptionInterface` from [`christianjbrown/php-oauth2-client-lib`](https://github.com/christianjbrown/php-oauth2-client-lib). Both are outside this library's exception hierarchy.

Under the hood, `Etsy` wires the clients, their transformer chains, and the OAuth refresh machinery through a [Symfony dependency-injection](https://symfony.com/doc/current/components/dependency_injection.html) container. If you don't want the container, you can build the same chain by hand — as shown below.

<details id="wiring-the-clients">
<summary><strong>Wiring the clients</strong></summary>

```php
use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptsTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptTransformer;
use ChristianBrown\Etsy\Transformer\RefundsTransformer;
use ChristianBrown\Etsy\Transformer\RefundTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentsTransformer;
use ChristianBrown\Etsy\Transformer\ShipmentTransformer;
use ChristianBrown\Etsy\Transformer\TransactionsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationsTransformer;
use ChristianBrown\Etsy\Transformer\TransactionVariationTransformer;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;

$shopId = 12345678;
$keystring = 'your-app-keystring';

$accessTokenStore = new MemoryKeyValueStore();
$refreshTokenStore = new MemoryKeyValueStore();
$refreshTokenStore->setValue('your-seed-refresh-token');

// Shared JSON request sender (wires Guzzle for you).
$requestSender = (new ApiClient())->getJsonApiRequestSender();

// OAuth2 refresh machinery → a two-header credential (x-api-key + Bearer token).
$refreshTokenManager = new RefreshTokenManager(
    $requestSender,
    $accessTokenStore,
    $refreshTokenStore,
    new AccessTokenTransformer(),
    'https://api.etsy.com/v3/public/oauth/token'
);
$credentials = new Credentials($refreshTokenManager, $keystring);

// Receipt transformer chain. The single Money transformer is shared across every
// money field; the singular Receipt transformer is wrapped by ReceiptsTransformer
// for the list endpoint and used directly for getOneById().
$moneyTransformer = new MoneyTransformer();

$transactionTransformer = new TransactionTransformer(
    $moneyTransformer,
    new TransactionVariationsTransformer(new TransactionVariationTransformer()),
    new ListingPropertyValuesTransformer(new ListingPropertyValueTransformer())
);

$receiptTransformer = new ReceiptTransformer(
    $moneyTransformer,
    new TransactionsTransformer($transactionTransformer),
    new RefundsTransformer(new RefundTransformer($moneyTransformer)),
    new ShipmentsTransformer(new ShipmentTransformer())
);

$shopReceiptApi = new ShopReceiptApi(
    $requestSender,
    $receiptTransformer,
    new ReceiptsTransformer($receiptTransformer),
    $credentials,
    $shopId
);
```

</details>

## :page_facing_up: License

Released under the [MIT License](LICENSE).
