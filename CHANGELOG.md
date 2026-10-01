# Changelog

All notable changes to this package are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package uses
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `EtsyFactory` (and `EtsyFactoryInterface`), the one place that builds the default object graph.
  `(new EtsyFactory())->create($shopId, $key, $sharedSecret, $accessTokenStore, $refreshTokenStore)` returns
  an `EtsyInterface`; `createForHost()` takes the same arguments plus an `EtsyHostInterface` to point at a
  different host.
- `HostRewritingApiRequestSender` and `HostRewritingJsonApiRequestSender` now implement the `patchMultipart`,
  `postMultipart` and `putMultipart` methods of api-client 3 and rewrite the host on them too.
- `ListingWithAssociationsFieldsTransformerInterface` and eleven small field transformers that
  `ListingWithAssociationsTransformer` now composes, one per group of related listing fields.

### Changed

- `Etsy` no longer builds anything. Its constructor takes a single `Psr\Container\ContainerInterface`
  holding the services. Use `EtsyFactory` to get a ready-made instance.
- `ListingWithAssociationsTransformer` takes an array of `ListingWithAssociationsFieldsTransformerInterface`
  instead of eleven transformers. Its output is unchanged.
- Moves to christianjbrown/api-client ^3.0, christianjbrown/oauth2-client ^2.1 and christianjbrown/key-value-store
  ^3.0, and requires symfony/clock ^8.0. Consumers now get those majors. The key-value stores need a PSR-20
  clock (`new MemoryKeyValueStore(new NativeClock())`), and the in-memory store now enforces TTLs.
- The resource clients type their senders as narrowly as they can: clients that only read take
  `JsonReadApiRequestSenderInterface` and `ReadApiRequestSenderInterface`. The combined interfaces still satisfy them.

### Removed

- `EtsyInterface::SERVICE_ACCESS_TOKEN_TRANSFORMER`. The token transformer is now built inside oauth2-client's
  `RefreshTokenManagerFactory`.

## [1.1.1] - 2026-09-30

### Changed

- Allows christianjbrown/key-value-store 2.0 as well as 1.x. Nothing this package uses from it changed.

## [1.1.0] - 2026-09-28

Covers every parameter and field in Etsy's current Open API v3 spec. All additions are optional or appended,
so existing calls keep working.

### Added

- The EU commercial guarantee (`ecgt_*`) fields on listings, and the matching fields when creating or
  updating a listing.
- The missing listing search and lookup parameters (sort, filters, includes and legacy) on the listing
  client.
- `show_deleted`, `includes` and `legacy` on listing inventory reads.
- `is_multi_video` on the video upload endpoint.
- Pagination on `getShopReadinessStateDefinitions`.
- The `legacy` parameter on receipt transaction reads.
- The missing search filters on the receipt list and page endpoints.

## [1.0.0] - 2026-09-28

First stable release.

### Added

- A typed client for the Etsy Open API v3 covering every operation in the published spec: all `GET`
  endpoints and every `POST`, `PUT`, `PATCH` and `DELETE` write. Reads return typed models and writes take
  typed request objects; `DELETE` operations return `void`.
- Reads for receipts and their transactions, payments and ledger entries, listings (with files, images,
  videos, variation images, properties, inventory, translations and personalization), shops, shop sections,
  seller and buyer taxonomy, reviews, return policies, production partners, holiday preferences, readiness
  state definitions, shipping profiles and carriers, users and addresses, and ping.
- Writes for draft listings and their images, files, videos, inventory, personalization, properties,
  translations and variation images, plus shop sections, return policies, readiness state definitions,
  shipping profiles with their destinations and upgrades, receipt updates and shipments, with multipart
  support for uploads.
- `getPage()` on the shop receipt client, which returns a page of receipts with Etsy's total receipt count so
  a whole shop can be walked without guessing where the last page is.
- An optional `EtsyHostInterface` argument on the `Etsy` constructor to point the API and OAuth token hosts
  somewhere other than production.
- The `Etsy` constructor takes the app's shared secret, which is sent with the keystring in `x-api-key` as
  Etsy requires. The access-token store must be a `TtlAwareKeyValueStoreInterface`, because the token refresh
  reads and writes a TTL.
- Nine narrow per-domain role interfaces (listings, shop, receipts, taxonomy, users, payments, reviews,
  shipping, ping) that `EtsyInterface` extends, so code can depend on only the client it uses.
- In-memory response caching in the API clients.
- A single exception hierarchy, so callers do not depend on the underlying HTTP client.

[Unreleased]: https://github.com/christianjbrown/etsy-open-api-sdk-php/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/christianjbrown/etsy-open-api-sdk-php/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/christianjbrown/etsy-open-api-sdk-php/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/christianjbrown/etsy-open-api-sdk-php/releases/tag/v1.0.0
