# CLAUDE.md

Guidance for working in this repository. Match the existing conventions exactly — this codebase is
small, uniform, and highly opinionated, so new code should be indistinguishable from what's here and
from its sibling libraries (`smartthings-api-sdk`, `met-office-weather-datahub-api-sdk`).

## What this is

A strongly-typed, **read-only** PHP 8.5+ client for the [Etsy Open API v3](https://developers.etsy.com/documentation/).
It wraps the API's `GET` endpoints, returning typed model objects instead of raw arrays. Writes
(POST/PUT/DELETE) are intentionally **out of scope** for now. The primary entry point is the `Etsy`
facade (`src/Etsy.php`), which wires the resource clients, their transformer chains, and the OAuth
token-refresh machinery through a Symfony `ContainerBuilder` DI container. Hand-wiring the same
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
PHPStan, and the PHPUnit suite with coverage on every push/PR. Always run `composer fix-style` first,
then `composer check-style`, then `composer stan`, then `composer test` before finishing.

## Architecture

Layers under `src/`, mirrored 1:1 under `tests/`, plus the top-level `Etsy` facade. PSR-4:
`ChristianBrown\Etsy\` → `src/`, `ChristianBrown\Etsy\Tests\` → `tests/`.

- **`Etsy`** (`src/Etsy.php`) — the facade/entry point. Constructed with `(int $shopId, string $key,
  TtlAwareKeyValueStoreInterface $accessTokenStore, KeyValueStoreInterface $refreshTokenStore)` (the
  access token store must be TTL-aware because that is what `RefreshTokenManager` takes), it builds a
  `ContainerBuilder`, registers the core services, every transformer chain, then every resource
  client (service ids are `SERVICE_*` constants on `EtsyInterface`), and exposes `getShopReceiptApi()`
  etc. Getters are PHPStan-safe: assign `$this->container->get(...)` to a local `$service` with a
  `/** @var XApiInterface $service */` docblock, then return it.
- **`Auth/`** — `Credentials` (a value object over the OAuth `RefreshTokenManager` + keystring). Its
  `toHeaders()` returns the **two** headers every request needs: `x-api-key` (the keystring) and
  `Authorization: Bearer <access_token>` (a cached or freshly-refreshed OAuth2 token). This is the
  Etsy analog of SmartThings' `Token`/MetOffice's `ApiKey` — the difference is Etsy needs two headers
  and a dynamic, self-refreshing token.
- **`Api/`** — one `final` resource client per Etsy resource group (`ShopReceiptApi`, …), each
  implementing its interface which `extends ApiInterface`. Constructor order: the
  `JsonApiRequestSenderInterface` (from `christianjbrown/api-client` — no Guzzle/PSR-18 used
  directly), then its transformer(s), then the `CredentialsInterface`, then the injected `int $shopId`
  (shop_id is constructor-level; per-resource ids like `receipt_id` are method arguments). Each
  method: builds headers via `$this->credentials->toHeaders()`, calls `$this->requestSender->get($url,
  $query, $headers)`, defensively validates the response shape (throwing `UnexpectedResponseException`),
  delegates the payload to a transformer, and caches by id/key. List endpoints validate the response
  envelope key (e.g. `results`) and return `array<int, XInterface>` via a plural collection
  transformer; single-object endpoints guard against an empty response and return one `XInterface`.
  Where a caller needs to page through a whole result set, a **page** variant hands the entire
  envelope to a page transformer and returns a model carrying Etsy's `count` (the shop-wide total)
  alongside the results — see `ShopReceiptApi::getPage()` / `ReceiptPageTransformer` / `ReceiptPage`.
  Unlike the plain list methods, a page tolerates an empty `results` array so a count-driven loop
  never trips over a final empty page.
  Full URLs live in `API_URL*_SPRINTF` constants on the interface (the base host is
  `https://openapi.etsy.com`; note the OAuth token endpoint is on a different host,
  `https://api.etsy.com`, held as `EtsyInterface::OAUTH_TOKEN_URL`).
- **`Transformer/`** — turn raw decoded-JSON arrays into `Model` objects. Nested transformers are
  constructor-injected and composed into a chain (e.g. `ReceiptsTransformer` → `ReceiptTransformer` →
  `TransactionsTransformer`/`RefundsTransformer`/`ShipmentsTransformer`/`MoneyTransformer` → leaves).
  A single shared `MoneyTransformer` serves every money field across the graph.
- **`Model/`** — plain, mutable typed DTOs with getters and fluent setters.
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

1. Add the `Model` DTO(s) + interface(s) (constants, if any, on the interface).
2. Add the `Transformer`(s) + interfaces, with `KEY_*`/`*_SPRINTF`/`ARRAY_NAME` constants on the
   interface. Reuse the shared `MoneyTransformer` and other existing leaf transformers where the
   schema overlaps.
3. Add the `Api` client + interface (`API_URL*` constants, `extends ApiInterface`), taking the
   request sender, its transformer(s), the `CredentialsInterface`, and `int $shopId` (if shop-scoped).
4. Register the transformer chain and the client in `Etsy::init()` with new `SERVICE_*` ids on
   `EtsyInterface`, and add the `getXApi()` getter.
5. Add matching `#[CoversClass]` tests under `tests/<Layer>/`, plus the endpoint to the README table.
6. Run `composer fix-style`, then `composer check-style`, `composer stan`, and `composer test`, and
   **confirm the coverage report is 100%** on lines, paths, methods, and branches.

The Etsy Open API v3 OpenAPI spec (the source of truth for every field and endpoint) is at
`https://www.etsy.com/openapi/generated/oas/3.0.0.json`. `GET`-only: skip write endpoints for now.
