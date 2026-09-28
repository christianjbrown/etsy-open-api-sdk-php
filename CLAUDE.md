# CLAUDE.md

Guidance for working in this repository. Match the existing conventions exactly — this codebase is
small, uniform, and highly opinionated, so new code should be indistinguishable from what's here and
from its sibling libraries (`smartthings-api-sdk`, `met-office-weather-datahub-api-sdk`).

## What this is

A strongly-typed PHP 8.5+ client for the [Etsy Open API v3](https://developers.etsy.com/documentation/),
covering every `GET`, `POST`, `PUT`, `PATCH` and `DELETE` operation in the published spec and
returning typed model objects instead of raw arrays. The primary entry point is the `Etsy` facade
(`src/Etsy.php`), which wires the resource clients, their transformer and serializer chains, and the
OAuth token-refresh machinery through a Symfony `ContainerBuilder` DI container. Hand-wiring the same
chains without the container is still fully supported (see the "Wiring the clients" section of
`README.md`).

## Commands

Binaries install into `bin/` (Composer `bin-dir`), not `vendor/bin/`. Both `bin/` and `vendor/` are
gitignored and Composer-installed, so run `composer install` first.

| Task | Command |
| --- | --- |
| Run tests + coverage (opens HTML report) | `composer test` |
| Run tests, no coverage | `php -d memory_limit=-1 ./bin/phpunit --no-coverage` |
| Run tests + coverage, no browser | `XDEBUG_MODE=coverage php -d memory_limit=-1 ./bin/phpunit` |
| Run one test | `php -d memory_limit=-1 ./bin/phpunit --filter ReceiptTransformerTest` |
| Static analysis | `composer stan` |
| Check code style | `composer check-style` |
| Auto-fix code style | `composer fix-style` |

After adding autoloadable files, run `composer dump-autoload` if the class isn't found.

Style tooling comes from the `christianjbrown/code-quality-scripts` dev dependency: `check-style`
lints with **PHP_CodeSniffer 4** using the **`ChristianBrown` standard**, and **php-cs-fixer**
(`@PhpCsFixer`/`@Symfony`) handles formatting. Static analysis is **PHPStan at `level: max`**
(`phpstan.neon.dist`). The **GitHub Actions CI workflow** (`.github/workflows/ci.yml`) runs style,
PHPStan, and the PHPUnit suite with coverage on every push/PR, then fails the build with
`bin/php-coverage-check` if line, path, method, or branch coverage drops below 100%. Always run `composer fix-style` first,
then `composer check-style`, then `composer stan`, then `composer test` before finishing.

## Architecture

Layers under `src/`, mirrored 1:1 under `tests/`, plus the top-level `Etsy` facade. PSR-4:
`ChristianBrown\Etsy\` → `src/`, `ChristianBrown\Etsy\Tests\` → `tests/`.

- **`Etsy`** (`src/Etsy.php`) — the facade/entry point and composition root. Constructed with
  `(int $shopId, string $key, string $sharedSecret, TtlAwareKeyValueStoreInterface $accessTokenStore,
  KeyValueStoreInterface $refreshTokenStore)` (the access token store must be TTL-aware because that
  is what `RefreshTokenManager` takes), the constructor builds the list of `ServiceRegistrarInterface`
  registrars in dependency order, hands them to a `ContainerFactory`, and keeps the `ContainerBuilder`
  it returns. It exposes `getShopReceiptApi()` etc. Getters are PHPStan-safe: assign
  `$this->container->get(...)` to a local `$service` with a `/** @var XApiInterface $service */`
  docblock, then return it. `Etsy` itself is the only place in the library allowed to `new` a
  registrar — everywhere else takes its collaborators through the constructor.
- **`DependencyInjection/`** — `ServiceRegistrarInterface` (`register(ContainerBuilder $container): void`)
  and `ContainerFactory` (`create(): ContainerBuilder`, runs every registrar it was given, in order,
  against one container). `DependencyInjection/Registrar/` holds one registrar per resource group or
  concern (e.g. `ReceiptTransformersRegistrar`, `ApiClientsRegistrar`), each a direct, mechanical
  extraction of what used to be a private `Etsy::register*()` method — same `SERVICE_*` ids, same
  `setArguments()`/`getDefinition()` calls, just against the container the factory passes in instead
  of `$this->container`. `CoreServiceRegistrar` and `ApiClientsRegistrar` take constructor arguments
  (`$key`/`$sharedSecret`/the token stores, and `$shopId`, respectively); every other registrar takes
  none. Adding a resource group means adding a registrar and listing it in `Etsy`'s constructor, not
  editing a shared method.
- **`Role/`** — narrow `Etsy*AwareInterface`s, one per resource domain (listings, shop, receipts,
  taxonomy, users, payments, reviews, shipping, ping), each declaring only the `getXApi()` getters
  for that domain. `EtsyInterface` extends all of them, so a consumer that only needs, say, receipts
  can type-hint `EtsyReceiptsAwareInterface` instead of the full facade.
- **`Auth/`** — `Credentials` (a value object over the OAuth `RefreshTokenManager` + keystring). Its
  `toHeaders()` returns the **two** headers every request needs: `x-api-key` (the keystring) and
  `Authorization: Bearer <access_token>` (a cached or freshly-refreshed OAuth2 token). This is the
  Etsy analog of SmartThings' `Token`/MetOffice's `ApiKey` — the difference is Etsy needs two headers
  and a dynamic, self-refreshing token.
- **`Api/`** — one `final` resource client per Etsy resource group (`ShopReceiptApi`, …), each
  implementing its interface. Constructor order: the
  `JsonApiRequestSenderInterface` (from `christianjbrown/api-client` — no Guzzle/PSR-18 used
  directly), then `ApiRequestSenderInterface` if the client has any `DELETE` or multipart-upload
  method (see "Writes" below), then its transformer(s), then any `MultipartFormDataBuilderInterface`
  and `JsonToArrayTransformerInterface` an upload method needs, then its request serializer(s), then
  the `CredentialsInterface`, then the injected `int $shopId` (shop_id is constructor-level;
  per-resource ids like `receipt_id` are method arguments).
  Read methods: build headers via `$this->credentials->toHeaders()`, call
  `$this->requestSender->get($url, $query, $headers)`, defensively validate the response shape
  (throwing `UnexpectedResponseException`), delegate the payload to a transformer, and cache by
  id/key. List endpoints validate the response envelope key (e.g. `results`) and return
  `array<int, XInterface>` via a plural collection transformer; single-object endpoints guard against
  an empty response and return one `XInterface`. Where a caller needs to page through a whole result
  set, a **page** variant hands the entire envelope to a page transformer and returns a model
  carrying Etsy's `count` (the shop-wide total) alongside the results — see
  `ShopReceiptApi::getPage()` / `ReceiptPageTransformer` / `ReceiptPage`. Unlike the plain list
  methods, a page tolerates an empty `results` array so a count-driven loop never trips over a final
  empty page.
  Full URLs live in `API_URL*_SPRINTF` constants on the interface (the base host is
  `https://openapi.etsy.com`; note the OAuth token endpoint is on a different host,
  `https://api.etsy.com`, held as `EtsyInterface::OAUTH_TOKEN_URL`).
- **Writes** — `create`/`update` methods serialize a `Model\XRequest` through its `Serializer\XRequestSerializer`
  and call `$this->requestSender->postForm()`/`putForm()`/`patchForm()` (form-urlencoded body — most
  write endpoints) or `->post()`/`->put()` (JSON body — endpoints whose spec `requestBody` is
  `application/json`, e.g. `updateListingInventory`), then transform and cache the response exactly
  like a read. `DELETE` methods return `void`, take no body, and go through the **raw**
  `ApiRequestSenderInterface::delete()` rather than the JSON sender: Etsy replies `204 No Content` on
  a successful delete, and `JsonApiRequestSenderInterface` always tries to `json_decode` the body, so
  it throws `ParseJsonExceptionInterface` on a genuinely empty response. The three multipart uploads
  (`ListingFileApi::upload()`, `ListingImageApi::upload()`, `ListingVideoApi::upload()`) go through
  `ApiRequestSenderInterface::post()` too, for the same reason in reverse: `JsonApiRequestSenderInterface`
  only accepts an array body and JSON-encodes it, so it cannot send a pre-built `multipart/form-data`
  payload. An upload method builds the body with `MultipartFormDataBuilderInterface::build()` (scalar
  fields from the request serializer, plus the `Model\MultipartFileInterface` field when bytes are
  being uploaded), sets the `Content-Type` header from
  `MultipartFormDataBuilderInterface::toContentTypeHeaderValue()`, and decodes the raw string response
  itself via the api-client package's own `JsonToArrayTransformerInterface` (constructed directly, not
  behind a wrapper — same pattern `JsonApiRequestSender` uses internally). `christianjbrown/api-client`
  already ships `put`/`patch`/`delete`/`putForm`/`patchForm` on both senders, so nothing in that
  package needed extending for this.
- **`Transformer/`** — turn raw decoded-JSON arrays into `Model` objects. Nested transformers are
  constructor-injected and composed into a chain (e.g. `ReceiptsTransformer` → `ReceiptTransformer` →
  `TransactionsTransformer`/`RefundsTransformer`/`ShipmentsTransformer`/`MoneyTransformer` → leaves).
  A single shared `MoneyTransformer` serves every money field across the graph.
- **`Serializer/`** — the write-side mirror of `Transformer/`: turn a `Model\XRequest` into the array
  shape an `Api` client hands to the request sender. A `serialize(XRequestInterface): array` method,
  one per request model, with `KEY_*` constants on the interface. Two return shapes depending on the
  endpoint's content type: `array<string, string>` for a form-urlencoded body (built field-by-field
  via private `applyX(array $data, XRequestInterface $request): array` helpers, skipping optional
  fields that are `null`/empty exactly like a transformer skips absent input, and encoding non-string
  scalars through `Http\FormValueEncoderInterface`), or `array<string, mixed>` for a JSON body (plain
  nested arrays, no `FormValueEncoderInterface` involved — `JsonApiRequestSenderInterface` JSON-encodes
  it). A serializer whose request has a repeated nested object (e.g. `UpdateListingInventoryRequest`'s
  `products`) delegates to a plural `XsSerializer` that loops the singular one, mirroring the
  transformer layer's singular/plural split.
- **`Http/`** — `FormValueEncoderInterface` (`encodeBool`/`encodeInt`/`encodeFloat`, plus
  `encodeIntList`/`encodeStringList` for PHP's bracketed repeated-field notation,
  `tags[0]=one&tags[1]=two`, which is how Etsy's form-encoded endpoints take an array field) and
  `MultipartFormDataBuilderInterface` (renders a `multipart/form-data` body: one part per scalar
  field, then the file part when bytes are being uploaded).
- **`Model/`** — plain, mutable typed DTOs with getters and fluent setters. Request models
  (`Model/XRequest.php`, doc'd "The body of an `operationId` call") follow the same shape: required
  spec fields are constructor args, optional fields default `null`/`[]`. Every field name and
  requiredness comes from the OpenAPI spec's `requestBody` schema for that operation — never from
  memory or from a same-named response field, since request and response shapes for the same resource
  can differ (e.g. `is_personalizable` is a real *response* field on `Listing` but is not accepted as
  *input* by `createDraftListing` or `updateListing`).
- **`Exception/`** — `final` exception classes + matching interfaces, each extending the library-wide
  `ExceptionInterface` (which extends `Throwable`): `UnexpectedResponseException` (extends
  `RuntimeException`, thrown by clients and transformers for malformed responses) and
  `MissingInputException` (extends `InvalidArgumentException`, thrown for bad caller input).

## Conventions (follow all of these)

- `declare(strict_types=1);` on every file, immediately after `<?php`.
- **Every concrete class is `final` and implements a matching `...Interface`** in the same namespace.
  No abstract base classes — composition over inheritance.
- **Constants live on the interface, not the class**: URLs (`API_URL*`), JSON keys (`KEY_*`), service
  ids (`SERVICE_*`), collection names (`ARRAY_NAME`), and sprintf message templates (`*_SPRINTF`).
  Typed constants (`public const string KEY_… = '…';`). No string literal message text in a class body.
- **No constructor property promotion** — declare typed `private` properties and assign them in the
  constructor body. Class members (properties then methods) are ordered **alphabetically**.
- Import functions with `use function is_array;` etc. (after class imports, blank line between), and
  call them unqualified.
- **Models**: required fields are constructor args; optionals default (`?string $x = null`,
  `array $items = []`). Getters `getX()`; fluent setters `setX($value)` (param literally `$value`)
  that `return $this` typed as the **interface**. No enums, no `readonly`, no immutability.
- **Transformers**: one `transform(array $data): ...` method. Object transformers return a model
  interface; collection transformers (plural names) return `array`, looping with an indexed `for`
  over `array_values($data)` and delegating to the singular transformer, throwing
  `UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME))` on a
  non-array element. Guard required fields with a presence check then a type check (each its own `if`,
  never a compound `&&`/`||`), throwing `UnexpectedResponseException`. Use `empty()` for the presence
  check on **string/array** fields, but **`isset()` for numeric and boolean fields** so a legitimate
  `0`/`0.0`/`false` isn't dropped. Optional fields are **silently skipped** when absent or wrong-typed,
  applied via private `applyX(Model $m, array $data): void` helpers. Whole-number floats the API may
  serialize as JSON ints are normalized through a `static toFloat(mixed): ?float` helper.
- **A method that does not use `$this` must be `static`** (called via `self::`) — enforced for private
  methods by the shared `RequireStaticPrivateMethodRule` PHPStan rule.
- Public methods that can throw carry `@throws` docblocks naming the concrete exception(s). Array
  shapes are documented with `@param mixed[]` / `@return array<int, XInterface>` docblocks.

### The `x-api-key` header

Etsy requires `x-api-key` to be the app **keystring and shared secret joined by
a colon**, not the keystring alone. The keystring on its own is rejected with
403 "Shared secret is required in x-api-key header." on every endpoint,
including `openapi-ping`. `Credentials` therefore takes both values, and the
`Etsy` facade takes the shared secret as its third constructor argument.

The keystring alone is still correct as the OAuth2 `client_id` on the token
endpoint, which is why a refresh can succeed while every API call 403s.

## Testing

The `phpunit.xml` config is strict (`requireCoverageMetadata`, `beStrictAboutCoverageMetadata`,
`failOnRisky`, `failOnWarning`, path coverage; `<source>` keeps `restrictNotices`/`restrictWarnings`
on but `ignoreIndirectDeprecations` so Symfony DI's deprecations don't fail the suite).

- **Coverage must stay at 100%** — line, path, method/function, and branch. Every defensive guard and
  every optional-field branch must be exercised.
- **The compound-condition and cartesian traps**: keep one condition per `if` (compound `||`/`&&`
  create phantom xdebug paths that cap coverage below 100%). For **wide** models (a receipt has ~40
  optional fields), do NOT test a cartesian product of fields — path coverage is per-method and each
  `applyX` is independent, so cover it linearly: one "all fields valid" case, then per field one
  "absent" and one "wrong-type" case (all others valid). Small leaf transformers (≤6 fields) may use
  the nested-loop cartesian style from `smartthings-api-sdk`'s `DeviceTransformerTest`.
- **Every test class needs a `#[CoversClass(...)]` attribute** (may list more than one — a
  transformer test covers both the transformer and the model it builds). Use PHPUnit **attributes,
  not annotations**: `#[CoversClass]`, `#[DataProvider]`, `#[TestWith]`.
- Tests mirror `src/` 1:1 under `tests/<Layer>/`, one `final class XTest extends TestCase` per class.
  Mock collaborators with `self::createMock(...)` (or `self::createStub(...)` for pure return-value
  doubles); assert statically (`self::assertSame`). Reference the **same interface constants**
  production code uses — for both data keys and expected exception messages — so no strings are
  hardcoded.

## Adding a feature (a new resource / endpoint)

For a read (`GET`) endpoint:

1. Add the `Model` DTO(s) + interface(s) (constants, if any, on the interface).
2. Add the `Transformer`(s) + interfaces, with `KEY_*`/`*_SPRINTF`/`ARRAY_NAME` constants on the
   interface. Reuse the shared `MoneyTransformer` and other existing leaf transformers where the
   schema overlaps.
3. Add the `Api` client + interface (`API_URL*` constants), taking the
   request sender, its transformer(s), the `CredentialsInterface`, and `int $shopId` (if shop-scoped).
4. Register the transformer chain and the client with a `ServiceRegistrarInterface` registrar (see
   `DependencyInjection/`) with new `SERVICE_*` ids on `EtsyInterface`, and add the `getXApi()`
   getter. Add its interface to the narrow role interface it belongs with in `EtsyInterface.php`.
5. Add matching `#[CoversClass]` tests under `tests/<Layer>/`, plus the endpoint to the README table.
6. Run `composer fix-style`, then `composer check-style`, `composer stan`, and `composer test`, and
   **confirm the coverage report is 100%** on lines, paths, methods, and branches.

For a write (`POST`/`PUT`/`PATCH`/`DELETE`) endpoint, additionally:

1. If the endpoint takes a body, add a `Model\XRequest` + interface, built strictly from the spec
   operation's `requestBody` schema (field names, types and requiredness — never from memory, and
   never assumed from a same-named response field). Skip the model entirely for a body of one or two
   plain scalars with no dedicated schema (e.g. `consolidateShopReturnPolicies`) — build that inline
   in the `Api` method instead.
2. Add the matching `Serializer\XRequestSerializer` + interface (see "Writes" above for the
   form-vs-JSON return shape). Reuse `Http\FormValueEncoderInterface` for non-string scalars in a
   form body.
3. Add the `Api` client method. `DELETE` and any multipart upload go through the raw
   `ApiRequestSenderInterface` (constructor-injected alongside the JSON sender); every other write
   goes through `JsonApiRequestSenderInterface`. Invalidate/refresh whatever this client's own caches
   hold for the affected id after a successful write.
4. Register the new `Serializer` (and `Http\FormValueEncoderInterface`/
   `Http\MultipartFormDataBuilderInterface`/the raw `ApiRequestSenderInterface`/api-client's
   `JsonToArrayTransformer` if this is the first write on that client) with new `SERVICE_*` ids on
   `EtsyInterface`, and add them to the client's constructor args in
   `DependencyInjection\Registrar\ApiClientsRegistrar::register()`.
5. Add matching `#[CoversClass]` tests, plus the endpoint to the README table with its HTTP verb and
   required OAuth scope.
6. Run the same `composer fix-style` → `check-style` → `stan` → `test` gate and confirm 100% coverage.

The Etsy Open API v3 OpenAPI spec (the source of truth for every operation, field name and required
scope) is at `https://www.etsy.com/openapi/generated/oas/3.0.0.json`.
