# Changelog

All notable changes to this package are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the package uses
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/christianjbrown/etsy-open-api-sdk-php/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/christianjbrown/etsy-open-api-sdk-php/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/christianjbrown/etsy-open-api-sdk-php/releases/tag/v1.0.0
