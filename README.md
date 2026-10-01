# Etsy Open API v3 SDK

[![CI](https://github.com/christianjbrown/etsy-open-api-sdk-php/actions/workflows/ci.yml/badge.svg)](https://github.com/christianjbrown/etsy-open-api-sdk-php/actions/workflows/ci.yml) [![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/christianjbrown/etsy-open-api-sdk-php/actions/workflows/ci.yml) [![Packagist](https://img.shields.io/packagist/v/christianjbrown/etsy-open-api-sdk)](https://packagist.org/packages/christianjbrown/etsy-open-api-sdk) [![License](https://img.shields.io/packagist/l/christianjbrown/etsy-open-api-sdk)](https://github.com/christianjbrown/etsy-open-api-sdk-php/blob/main/LICENSE) [![PHP](https://img.shields.io/packagist/dependency-v/christianjbrown/etsy-open-api-sdk/php)](https://packagist.org/packages/christianjbrown/etsy-open-api-sdk)

A strongly-typed PHP client for the [Etsy Open API v3](https://developers.etsy.com/documentation/). It reads and writes your shop's data — receipts, listings, shipping profiles, and more — returning plain, typed model objects rather than raw arrays, and taking plain, typed request objects for anything you create or update.

Every operation in Etsy's published OpenAPI spec is covered: all `GET` endpoints, and every `POST`, `PUT`, `PATCH` and `DELETE` write. A write beyond the shop the client is configured for needs the matching OAuth scope granted to your access token (see "Prerequisites" below) — the read side currently supports:

- **Reading shop receipts** — a page of the shop's receipts (`getMultiple`), a page plus the shop's total receipt count so you can walk the whole set (`getPage`), or a single receipt by id (`getOneById`). Each receipt carries the full order: buyer and address fields, the money totals (grandtotal, subtotal, shipping, tax, VAT, discount, gift wrap), and its nested transactions, refunds, and shipments.

### Supported read endpoints

| Resource | Client | Endpoint(s) | Returns |
| --- | --- | --- | --- |
| Shop receipts | `getShopReceiptApi()` | `GET /shops/{shop_id}/receipts`, `GET /shops/{shop_id}/receipts/{receipt_id}` | `ReceiptInterface[]` / `ReceiptPageInterface` / `ReceiptInterface` |
| Shop receipt transactions | `getShopReceiptTransactionApi()` | `GET /shops/{shop_id}/transactions/{transaction_id}`, `GET /shops/{shop_id}/receipts/{receipt_id}/transactions`, `GET /shops/{shop_id}/listings/{listing_id}/transactions`, `GET /shops/{shop_id}/transactions` | `TransactionInterface` / `TransactionInterface[]` |
| Payments | `getPaymentApi()` | `GET /shops/{shop_id}/payments`, `GET /shops/{shop_id}/receipts/{receipt_id}/payments`, `GET /shops/{shop_id}/payment-account/ledger-entries/payments` | `PaymentInterface[]` |
| Ledger entries | `getLedgerEntryApi()` | `GET /shops/{shop_id}/payment-account/ledger-entries`, `GET /shops/{shop_id}/payment-account/ledger-entries/{ledger_entry_id}` | `PaymentAccountLedgerEntryInterface[]` / `PaymentAccountLedgerEntryInterface` |
| Listings | `getShopListingApi()` | `GET /listings/{listing_id}`, `GET /shops/{shop_id}/listings`, `GET /listings/active`, `GET /shops/{shop_id}/listings/active`, `GET /listings/batch`, `GET /shops/{shop_id}/listings/featured`, `GET /shops/{shop_id}/policies/return/{return_policy_id}/listings`, `GET /shops/{shop_id}/receipts/{receipt_id}/listings`, `GET /shops/{shop_id}/shop-sections/listings` | `ListingInterface` / `ListingInterface[]` |
| Batch listings with associations | `getListingBatchApi()` | `GET /listings/batch/inventory`, `GET /listings/batch/shipping` | `ListingWithAssociationsInterface[]` |
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
| Seller taxonomy | `getSellerTaxonomyApi()` | `GET /seller-taxonomy/nodes`, `GET /seller-taxonomy/nodes/{taxonomy_id}/properties` | `SellerTaxonomyNodeInterface[]` / `TaxonomyNodePropertyInterface[]` |
| Buyer taxonomy | `getBuyerTaxonomyApi()` | `GET /buyer-taxonomy/nodes`, `GET /buyer-taxonomy/nodes/{taxonomy_id}/properties` | `BuyerTaxonomyNodeInterface[]` / `BuyerTaxonomyNodePropertyInterface[]` |
| Reviews | `getReviewApi()` | `GET /shops/{shop_id}/reviews`, `GET /listings/{listing_id}/reviews` | `ReviewInterface[]` |
| Shop return policies | `getShopReturnPolicyApi()` | `GET /shops/{shop_id}/policies/return`, `GET /shops/{shop_id}/policies/return/{return_policy_id}` | `ShopReturnPolicyInterface[]` / `ShopReturnPolicyInterface` |
| Shop production partners | `getShopProductionPartnerApi()` | `GET /shops/{shop_id}/production-partners` | `ShopProductionPartnerInterface[]` |
| Shop holiday preferences | `getShopHolidayPreferenceApi()` | `GET /shops/{shop_id}/holiday-preferences` | `ShopHolidayPreferenceInterface[]` |
| Shop readiness state definitions | `getShopReadinessStateDefinitionApi()` | `GET /shops/{shop_id}/readiness-state-definitions`, `GET /shops/{shop_id}/readiness-state-definitions/{readiness_state_definition_id}` | `ShopReadinessStateDefinitionInterface[]` / `ShopReadinessStateDefinitionInterface` |
| Shipping profiles | `getShippingProfileApi()` | `GET /shops/{shop_id}/shipping-profiles`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}/destinations`, `GET /shops/{shop_id}/shipping-profiles/{shipping_profile_id}/upgrades`, `GET /shipping-carriers?origin_country_iso=…` | `ShopShippingProfileInterface[]` / `ShopShippingProfileInterface` / `ShopShippingProfileDestinationInterface[]` / `ShopShippingProfileUpgradeInterface[]` / `ShippingCarrierInterface[]` |
| Users | `getUserApi()` | `GET /users/{user_id}`, `GET /users/me` | `UserInterface` |
| User addresses | `getUserAddressApi()` | `GET /user/addresses`, `GET /user/addresses/{user_address_id}` | `UserAddressInterface[]` / `UserAddressInterface` |
| Ping | `getPingApi()` | `GET /openapi-ping` | `PingInterface` |

### Supported write endpoints

Every write takes a `Model\XRequest` object (constructed directly, or via its fluent setters for the optional fields) and returns the same typed model a read would. `DELETE` operations return `void`. See "Prerequisites" below for how OAuth scopes work.

| Verb | Endpoint | Required scope | Client · Method |
| --- | --- | --- | --- |
| `POST` | `/shops/{shop_id}/listings` | `listings_w` | `getShopListingApi()->create()` |
| `PATCH` | `/shops/{shop_id}/listings/{listing_id}` | `listings_w` | `getShopListingApi()->update()` |
| `DELETE` | `/listings/{listing_id}` | `listings_d` | `getShopListingApi()->delete()` |
| `PUT` | `/shops/{shop_id}/listings/{listing_id}/properties/{property_id}` | `listings_w` | `getListingPropertyApi()->update()` |
| `DELETE` | `/shops/{shop_id}/listings/{listing_id}/properties/{property_id}` | `listings_w` | `getListingPropertyApi()->delete()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/files` | `listings_w` | `getListingFileApi()->upload()` |
| `DELETE` | `/shops/{shop_id}/listings/{listing_id}/files/{listing_file_id}` | `listings_w` | `getListingFileApi()->delete()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/images` | `listings_w` | `getListingImageApi()->upload()` |
| `DELETE` | `/shops/{shop_id}/listings/{listing_id}/images/{listing_image_id}` | `listings_w` | `getListingImageApi()->delete()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/videos` | `listings_w` | `getListingVideoApi()->upload()` |
| `DELETE` | `/shops/{shop_id}/listings/{listing_id}/videos/{video_id}` | `listings_w` | `getListingVideoApi()->delete()` |
| `PUT` | `/listings/{listing_id}/inventory` | `listings_w` | `getListingInventoryApi()->update()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/personalization` | `listings_w` | `getListingPersonalizationApi()->update()` |
| `DELETE` | `/shops/{shop_id}/listings/{listing_id}/personalization` | `listings_w` | `getListingPersonalizationApi()->delete()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/translations/{language}` | `listings_w` | `getListingTranslationApi()->create()` |
| `PUT` | `/shops/{shop_id}/listings/{listing_id}/translations/{language}` | `listings_w` | `getListingTranslationApi()->update()` |
| `POST` | `/shops/{shop_id}/listings/{listing_id}/variation-images` | `listings_w` | `getListingVariationImageApi()->update()` |
| `PUT` | `/shops/{shop_id}/receipts/{receipt_id}` | `transactions_w` | `getShopReceiptApi()->updateShopReceipt()` |
| `POST` | `/shops/{shop_id}/receipts/{receipt_id}/tracking` | `transactions_w` | `getShopReceiptApi()->createReceiptShipment()` |
| `PUT` | `/shops/{shop_id}` | `shops_r` + `shops_w` | `getShopApi()->updateShop()` |
| `PUT` | `/shops/{shop_id}/holiday-preferences/{holiday_id}` | `shops_w` | `getShopHolidayPreferenceApi()->updateHolidayPreference()` |
| `POST` | `/shops/{shop_id}/sections` | `shops_w` | `getShopSectionApi()->create()` |
| `PUT` | `/shops/{shop_id}/sections/{shop_section_id}` | `shops_w` | `getShopSectionApi()->update()` |
| `DELETE` | `/shops/{shop_id}/sections/{shop_section_id}` | `shops_w` | `getShopSectionApi()->delete()` |
| `POST` | `/shops/{shop_id}/policies/return` | `shops_w` | `getShopReturnPolicyApi()->create()` |
| `PUT` | `/shops/{shop_id}/policies/return/{return_policy_id}` | `shops_w` | `getShopReturnPolicyApi()->update()` |
| `DELETE` | `/shops/{shop_id}/policies/return/{return_policy_id}` | `shops_w` | `getShopReturnPolicyApi()->delete()` |
| `POST` | `/shops/{shop_id}/policies/return/consolidate` | `shops_w` | `getShopReturnPolicyApi()->consolidate()` |
| `POST` | `/shops/{shop_id}/readiness-state-definitions` | `shops_w` | `getShopReadinessStateDefinitionApi()->create()` |
| `PUT` | `/shops/{shop_id}/readiness-state-definitions/{readiness_state_definition_id}` | `shops_w` | `getShopReadinessStateDefinitionApi()->update()` |
| `DELETE` | `/shops/{shop_id}/readiness-state-definitions/{readiness_state_definition_id}` | `shops_w` | `getShopReadinessStateDefinitionApi()->delete()` |
| `POST` | `/shops/{shop_id}/shipping-profiles` | `shops_w` | `getShippingProfileApi()->create()` |
| `PUT` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}` | `shops_w` | `getShippingProfileApi()->update()` |
| `DELETE` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}` | `shops_w` | `getShippingProfileApi()->delete()` |
| `POST` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/destinations` | `shops_w` | `getShippingProfileApi()->createDestination()` |
| `PUT` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/destinations/{shipping_profile_destination_id}` | `shops_w` | `getShippingProfileApi()->updateDestination()` |
| `DELETE` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/destinations/{shipping_profile_destination_id}` | `shops_w` | `getShippingProfileApi()->deleteDestination()` |
| `POST` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/upgrades` | `shops_w` | `getShippingProfileApi()->createUpgrade()` |
| `PUT` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/upgrades/{upgrade_id}` | `shops_w` | `getShippingProfileApi()->updateUpgrade()` |
| `DELETE` | `/shops/{shop_id}/shipping-profiles/{shipping_profile_id}/upgrades/{upgrade_id}` | `shops_w` | `getShippingProfileApi()->deleteUpgrade()` |
| `DELETE` | `/user/addresses/{user_address_id}` | `address_r` | `getUserAddressApi()->delete()` |
| `POST` | `/scopes` | none | `getPingApi()->getScopes()` |

## :heavy_check_mark: Prerequisites

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.5 or higher (8.x)
- [Composer](https://getcomposer.org/)
- An Etsy OAuth **access token** carrying the scope(s) your calls need. Every read endpoint needs
  the matching `_r` scope (`listings_r`, `transactions_r`, `shops_r`, …); every write additionally
  needs the matching `_w` scope (`listings_w`, `listings_d` for `deleteListing`, `transactions_w`,
  `shops_w`), and `deleteUserAddress` needs `address_r`. See the "Supported write endpoints" table
  above for the exact scope each write call needs, and Etsy's own
  [scopes documentation](https://developers.etsy.com/documentation/essentials/authentication/#scopes)
  for how scopes are requested during the OAuth authorization flow. A token missing a scope gets a
  `403` from Etsy, not a client-side error — this library does not validate scopes locally.

:bulb: If you're on MacOS and have [Homebrew](https://brew.sh/), PHP and Composer will install with `brew install composer`.

## :building_construction: Installation

For your composer-enabled project:

```bash
composer require christianjbrown/etsy-open-api-sdk
```

## :computer: Usage

Etsy's Open API v3 authenticates every request with two pieces: your app's **keystring** (sent as the `x-api-key` header) and an **OAuth 2.0 access token** (sent as `Authorization: Bearer …`). Access tokens are short-lived, so this client refreshes them for you using a long-lived **refresh token** and the OAuth2 `refresh_token` grant.

You supply four things to `EtsyFactory::create()`:

- your numeric **shop id**,
- your app **keystring** (which Etsy also uses as the OAuth `client_id`),
- a **`TtlAwareKeyValueStoreInterface`** to hold the current access token (an in-memory store is fine — it's re-fetched as needed),
- a **`KeyValueStoreInterface`** holding your refresh token. This one must **persist** (a database, secret store, etc.), because Etsy rotates the refresh token on every refresh and the client writes the new value back. Seed it once with a refresh token obtained from Etsy's [OAuth authorization flow](https://developers.etsy.com/documentation/essentials/authentication).

```php
use ChristianBrown\Etsy\EtsyFactory;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;

// Access token: transient, an in-memory store is fine.
$accessTokenStore = new MemoryKeyValueStore();

// Refresh token: must persist and already hold a valid refresh token.
// Any KeyValueStoreInterface works (DatabaseKeyValueStore, FirestoreKeyValueStore, …).
$refreshTokenStore = new MemoryKeyValueStore();
$refreshTokenStore->setValue('your-seed-refresh-token');

$etsy = (new EtsyFactory())->create(
    12345678,                 // your shop id
    'your-app-keystring',     // OAuth client_id, and the first half of x-api-key
    'your-app-shared-secret', // the second half of x-api-key
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

`getMultiple()` hands back just the receipts on the page, which is enough when you
only want the most recent orders. To walk *every* receipt, use `getPage()` instead:
it returns a `ReceiptPageInterface` carrying the shop's total receipt count
alongside the page, so the loop knows when to stop without having to guess from a
short page.

```php
$limit = 100;
$offset = 0;
$soldPerListing = [];

do {
    $page = $shopReceiptApi->getPage(limit: $limit, offset: $offset);   // ReceiptPageInterface

    foreach ($page->getReceipts() as $receipt) {
        foreach ($receipt->getTransactions() as $transaction) {
            $listingId = $transaction->getListingId();
            if ($listingId === null) {
                continue;
            }
            $soldPerListing[$listingId] = ($soldPerListing[$listingId] ?? 0) + ($transaction->getQuantity() ?? 0);
        }
    }

    $offset += $limit;
} while ($offset < $page->getCount());
```

Writing data follows the same shape: build a `Model\XRequest`, and call the matching client method. Optional fields are set through fluent setters, exactly like a model's getters mirror the response:

```php
use ChristianBrown\Etsy\Model\ShopReturnPolicyRequest;

$shopReturnPolicyApi = $etsy->getShopReturnPolicyApi();

$request = (new ShopReturnPolicyRequest(acceptsReturns: true, acceptsExchanges: true))
    ->setReturnDeadline(30);

$policy = $shopReturnPolicyApi->create($request);   // ShopReturnPolicyInterface
echo $policy->getReturnPolicyId(), "\n";

$shopReturnPolicyApi->delete($policy->getReturnPolicyId());   // void
```

See "Supported write endpoints" above for the full list of write calls and their required OAuth scopes.

### Pointing at a different host

`EtsyFactory::create()` takes an optional sixth argument, an `EtsyHostInterface`, which defaults to
Etsy's production hosts. Pass a differently configured `EtsyHost` to point every request, and the
OAuth token refresh, somewhere else — a test double, a proxy, or a sandbox once Etsy publishes one:

```php
use ChristianBrown\Etsy\Host\EtsyHost;

$host = new EtsyHost(
    apiBaseUrl: 'https://openapi.etsy.com',                         // default; every API_URL* constant is rooted here
    oAuthTokenUrl: 'https://api.etsy.com/v3/public/oauth/token',     // default OAuth2 token endpoint
);

$etsy = (new EtsyFactory())->create(12345678, 'your-app-keystring', 'your-app-shared-secret', $accessTokenStore, $refreshTokenStore, $host);
```

Etsy's Open API v3 has no published sandbox at the time of writing (unlike, say, eBay's
`api.sandbox.ebay.com`/`apiz.sandbox.ebay.com`), so the only two hosts this library knows about are
the production ones above. `EtsyHost` exists so a test double or a future sandbox host can be
swapped in without editing any `Api/` class.

### Upgrading to 2.0

`Etsy` no longer builds its own services, so it is no longer constructed with the shop id, keys and
stores. Build it with `EtsyFactory` instead:

```php
// Before
$etsy = new Etsy(12345678, 'your-app-keystring', 'your-app-shared-secret', $accessTokenStore, $refreshTokenStore, $host);

// After
$etsy = (new EtsyFactory())->create(12345678, 'your-app-keystring', 'your-app-shared-secret', $accessTokenStore, $refreshTokenStore, $host);
```

`new Etsy($container)` now takes a PSR-11 container and is meant for code that wires its own graph.
If you construct `ListingWithAssociationsTransformer` yourself, it now takes an array of
`ListingWithAssociationsFieldsTransformerInterface` implementations.

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

Both live in `src/Exception/`. Request-level failures (network errors, non-2xx responses) surface as `RequestExceptionInterface` from [`christianjbrown/api-client`](https://github.com/christianjbrown/api-client-php); token-refresh failures surface as `RequestExceptionInterface` from [`christianjbrown/oauth2-client`](https://github.com/christianjbrown/oauth2-client-php). Both are outside this library's exception hierarchy.

Under the hood, `Etsy` wires the clients, their transformer chains, and the OAuth refresh machinery through a [Symfony dependency-injection](https://symfony.com/doc/current/components/dependency_injection.html) container. If you don't want the container, you can build the same chain by hand — as shown below.

<details id="wiring-the-clients">
<summary><strong>Wiring the clients</strong></summary>

```php
use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\Etsy\Api\ShopReceiptApi;
use ChristianBrown\Etsy\Auth\Credentials;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Serializer\CreateReceiptShipmentRequestSerializer;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestsSerializer;
use ChristianBrown\Etsy\Serializer\ReceiptShipmentCustomsItemRequestSerializer;
use ChristianBrown\Etsy\Serializer\UpdateShopReceiptRequestSerializer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValuesTransformer;
use ChristianBrown\Etsy\Transformer\ListingPropertyValueTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\ReceiptPageTransformer;
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
use ChristianBrown\Etsy\Http\FormValueEncoder;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;
use ChristianBrown\OAuth2Client\RefreshTokenManager;
use ChristianBrown\OAuth2Client\Transformer\AccessTokenTransformer;

$shopId = 12345678;
$keystring = 'your-app-keystring';
$sharedSecret = 'your-app-shared-secret';

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
$credentials = new Credentials($refreshTokenManager, $keystring, $sharedSecret);

// Receipt transformer chain. The single Money transformer is shared across every
// money field; the singular Receipt transformer is wrapped by ReceiptsTransformer
// for the list endpoint (and by ReceiptPageTransformer for getPage()) and used
// directly for getOneById().
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

$receiptsTransformer = new ReceiptsTransformer($receiptTransformer);

// Write-side serializers for createReceiptShipment()/updateShopReceipt().
$createReceiptShipmentRequestSerializer = new CreateReceiptShipmentRequestSerializer(
    new ReceiptShipmentCustomsItemRequestsSerializer(new ReceiptShipmentCustomsItemRequestSerializer())
);
$updateShopReceiptRequestSerializer = new UpdateShopReceiptRequestSerializer(new FormValueEncoder());

$shopReceiptApi = new ShopReceiptApi(
    $requestSender,
    $receiptTransformer,
    $receiptsTransformer,
    new ReceiptPageTransformer($receiptsTransformer),
    $createReceiptShipmentRequestSerializer,
    $updateShopReceiptRequestSerializer,
    new ResponseCache(), // getMultiple()
    new ResponseCache(), // getPage()
    new ResponseCache(), // getOneById()
    $credentials,
    $shopId
);
```

Every `Api/` class keeps its own in-memory response cache behind `ChristianBrown\Etsy\Cache\ResponseCacheInterface`, one instance per cache the class needs — `ShopReceiptApi` above has three, one each for `getMultiple()`, `getPage()` and `getOneById()`. `ResponseCache` is the plain implementation used by the container; a hand-wired client can use it too, or supply its own.

</details>

## :memo: Changelog

Notable changes in each release are listed in [CHANGELOG.md](CHANGELOG.md).



## :page_facing_up: License

Released under the [MIT License](LICENSE).
