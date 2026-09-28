<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopListingApi;
use ChristianBrown\Etsy\Api\ShopListingApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateDraftListingRequestInterface;
use ChristianBrown\Etsy\Model\ListingInterface;
use ChristianBrown\Etsy\Model\UpdateListingRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateDraftListingRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateListingRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ListingsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ListingTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopListingApi::class)]
#[UsesClass(ResponseCache::class)]
final class ShopListingApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testCreateReturnsListing(): void
    {
        $headers = ['x-api-key' => 'key'];
        $listingData = ['listing-self'];
        $listing = self::createStub(ListingInterface::class);
        $listing->method('getListingId')->willReturn(555);
        $serializedBody = ['title' => 'New listing'];

        $request = self::createStub(CreateDraftListingRequestInterface::class);
        $requestSerializer = self::createMock(CreateDraftListingRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($listingData);

        $listingTransformer = self::createMock(ListingTransformerInterface::class);
        $listingTransformer->expects(self::once())->method('transform')
            ->with($listingData)
            ->willReturn($listing);

        $api = $this->buildApi($headers, $requestSender, listingTransformer: $listingTransformer, createDraftListingRequestSerializer: $requestSerializer);

        self::assertSame($listing, $api->create($request));
    }

    public function testCreateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopListingApiInterface::UNEXPECTED_RESPONSE);

        $api->create(self::createStub(CreateDraftListingRequestInterface::class));
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_ID_SPRINTF, 555),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), apiRequestSender: $apiRequestSender);

        $api->delete(555);
    }

    public function testFindActiveByShopReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_ACTIVE_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->findActiveByShop(10, 5));
    }

    public function testFindActiveByShopSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->findActiveByShop(25, 0, true);
        $second = $api->findActiveByShop(25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testFindActiveByShopUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->findActiveByShop();
        $second = $api->findActiveByShop();

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testFindActiveByShopWithSortIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_ACTIVE_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_SORT_ON => 'price',
                    ShopListingApiInterface::KEY_SORT_ORDER => 'asc',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->findActiveByShop(10, 5, false, 'price', 'asc'));
    }

    public function testFindActiveReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopListingApiInterface::API_URL_ACTIVE,
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_KEYWORDS => 'mug',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->findActive('mug', 10, 5));
    }

    public function testFindActiveSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->findActive(null, 25, 0, true);
        $second = $api->findActive(null, 25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testFindActiveUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->findActive();
        $second = $api->findActive();

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testFindActiveWithAllFiltersIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopListingApiInterface::API_URL_ACTIVE,
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_KEYWORDS => 'mug',
                    ShopListingApiInterface::KEY_SORT_ON => 'created',
                    ShopListingApiInterface::KEY_SORT_ORDER => 'desc',
                    ShopListingApiInterface::KEY_MIN_PRICE => '1.5',
                    ShopListingApiInterface::KEY_MAX_PRICE => '99.5',
                    ShopListingApiInterface::KEY_TAXONOMY_ID => '7',
                    ShopListingApiInterface::KEY_SHOP_LOCATION => 'GB',
                    ShopListingApiInterface::KEY_IS_SAFE => 'true',
                    ShopListingApiInterface::KEY_CURRENCY => 'GBP',
                    ShopListingApiInterface::KEY_BUYER_COUNTRY => 'GB',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->findActive('mug', 10, 5, false, 'created', 'desc', 1.5, 99.5, 7, 'GB', true, 'GBP', 'GB'));
    }

    public function testFindActiveWithoutKeywordsOmitsKeywordsQuery(): void
    {
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopListingApiInterface::API_URL_ACTIVE,
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->findActive(null, 10, 5));
    }

    public function testGetByIdReturnsListing(): void
    {
        $listingData = ['listing-self'];
        $headers = ['x-api-key' => 'key'];
        $listing = self::createStub(ListingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_ID_SPRINTF, 500),
                [],
                $headers,
            )
            ->willReturn($listingData);

        $listingTransformer = self::createMock(ListingTransformerInterface::class);
        $listingTransformer->expects(self::once())->method('transform')
            ->with($listingData)
            ->willReturn($listing);

        $api = $this->buildApi($headers, $requestSender, $listingTransformer);

        self::assertSame($listing, $api->getById(500));
    }

    public function testGetByIdSkipCacheRefetches(): void
    {
        $listing = self::createStub(ListingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['listing-self']);

        $listingTransformer = self::createStub(ListingTransformerInterface::class);
        $listingTransformer->method('transform')->willReturn($listing);

        $api = $this->buildApi([], $requestSender, $listingTransformer);

        $first = $api->getById(500, true);
        $second = $api->getById(500, true);

        self::assertSame($listing, $first);
        self::assertSame($listing, $second);
    }

    public function testGetByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopListingApiInterface::UNEXPECTED_RESPONSE);

        $api->getById(500, true);
    }

    public function testGetByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopListingApiInterface::UNEXPECTED_RESPONSE);

        $api->getById(500);
    }

    public function testGetByIdUsesCacheOnSecondCall(): void
    {
        $listing = self::createStub(ListingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['listing-self']);

        $listingTransformer = self::createStub(ListingTransformerInterface::class);
        $listingTransformer->method('transform')->willReturn($listing);

        $api = $this->buildApi([], $requestSender, $listingTransformer);

        $first = $api->getById(500);
        $second = $api->getById(500);

        self::assertSame($listing, $first);
        self::assertSame($listing, $second);
    }

    public function testGetByIdWithFiltersIncludesQuery(): void
    {
        $listingData = ['listing-self'];
        $headers = ['x-api-key' => 'key'];
        $listing = self::createStub(ListingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_ID_SPRINTF, 500),
                [
                    ShopListingApiInterface::KEY_INCLUDES => 'Shop',
                    ShopListingApiInterface::KEY_LANGUAGE => 'en',
                    ShopListingApiInterface::KEY_ALLOW_SUGGESTED_TITLE => 'true',
                ],
                $headers,
            )
            ->willReturn($listingData);

        $listingTransformer = self::createMock(ListingTransformerInterface::class);
        $listingTransformer->expects(self::once())->method('transform')
            ->with($listingData)
            ->willReturn($listing);

        $api = $this->buildApi($headers, $requestSender, $listingTransformer);

        self::assertSame($listing, $api->getById(500, false, 'Shop', 'en', true));
    }

    public function testGetByListingIdsReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopListingApiInterface::API_URL_BATCH,
                [
                    ShopListingApiInterface::KEY_LISTING_IDS => '1,2,3',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByListingIds([1, 2, 3]));
    }

    public function testGetByListingIdsSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByListingIds([1, 2, 3], true);
        $second = $api->getByListingIds([1, 2, 3], true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByListingIdsUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByListingIds([1, 2, 3]);
        $second = $api->getByListingIds([1, 2, 3]);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByListingIdsWithFiltersIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShopListingApiInterface::API_URL_BATCH,
                [
                    ShopListingApiInterface::KEY_LISTING_IDS => '1,2,3',
                    ShopListingApiInterface::KEY_INCLUDES => 'Shop',
                    ShopListingApiInterface::KEY_CURRENCY => 'GBP',
                    ShopListingApiInterface::KEY_BUYER_COUNTRY => 'GB',
                    ShopListingApiInterface::KEY_LEGACY => 'true',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByListingIds([1, 2, 3], false, 'Shop', 'GBP', 'GB', true));
    }

    public function testGetByReceiptReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_RECEIPT_SPRINTF, self::SHOP_ID, 77),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByReceipt(77, 10, 5));
    }

    public function testGetByReceiptSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByReceipt(77, 25, 0, true);
        $second = $api->getByReceipt(77, 25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByReceiptUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByReceipt(77);
        $second = $api->getByReceipt(77);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByReceiptWithLegacyIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_RECEIPT_SPRINTF, self::SHOP_ID, 77),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_LEGACY => 'true',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByReceipt(77, 10, 5, false, true));
    }

    public function testGetByReturnPolicyReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_RETURN_POLICY_SPRINTF, self::SHOP_ID, 55),
                [],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByReturnPolicy(55));
    }

    public function testGetByReturnPolicySkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByReturnPolicy(55, true);
        $second = $api->getByReturnPolicy(55, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByReturnPolicyUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByReturnPolicy(55);
        $second = $api->getByReturnPolicy(55);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByReturnPolicyWithLegacyFalseIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_RETURN_POLICY_SPRINTF, self::SHOP_ID, 55),
                [ShopListingApiInterface::KEY_LEGACY => 'false'],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByReturnPolicy(55, false, false));
    }

    public function testGetByReturnPolicyWithLegacyIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_RETURN_POLICY_SPRINTF, self::SHOP_ID, 55),
                [ShopListingApiInterface::KEY_LEGACY => 'true'],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByReturnPolicy(55, false, true));
    }

    public function testGetByShopReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_STATE => 'active',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByShop('active', 10, 5));
    }

    public function testGetByShopSectionIdsReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_SHOP_SECTIONS_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_SHOP_SECTION_IDS => '7,8',
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByShopSectionIds([7, 8], 10, 5));
    }

    public function testGetByShopSectionIdsSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByShopSectionIds([7, 8], 25, 0, true);
        $second = $api->getByShopSectionIds([7, 8], 25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByShopSectionIdsUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByShopSectionIds([7, 8]);
        $second = $api->getByShopSectionIds([7, 8]);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByShopSectionIdsWithSortLegacyIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_SHOP_SECTIONS_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_SHOP_SECTION_IDS => '7,8',
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_SORT_ON => 'price',
                    ShopListingApiInterface::KEY_SORT_ORDER => 'asc',
                    ShopListingApiInterface::KEY_LEGACY => 'true',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByShopSectionIds([7, 8], 10, 5, false, 'price', 'asc', true));
    }

    public function testGetByShopSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByShop(null, 25, 0, true);
        $second = $api->getByShop(null, 25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByShopThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopListingApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopListingApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopListingApiInterface::KEY_RESULTS));

        $api->getByShop();
    }

    public function testGetByShopThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopListingApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopListingApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopListingApiInterface::KEY_RESULTS));

        $api->getByShop();
    }

    public function testGetByShopUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getByShop();
        $second = $api->getByShop();

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetByShopWithoutStateOmitsStateQuery(): void
    {
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByShop(null, 10, 5));
    }

    public function testGetByShopWithSortIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_STATE => 'active',
                    ShopListingApiInterface::KEY_SORT_ON => 'price',
                    ShopListingApiInterface::KEY_SORT_ORDER => 'asc',
                    ShopListingApiInterface::KEY_INCLUDES => 'Shop',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getByShop('active', 10, 5, false, 'price', 'asc', 'Shop'));
    }

    public function testGetFeaturedByShopReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_FEATURED_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getFeaturedByShop(10, 5));
    }

    public function testGetFeaturedByShopSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getFeaturedByShop(25, 0, true);
        $second = $api->getFeaturedByShop(25, 0, true);

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetFeaturedByShopUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, null, $listingsTransformer);

        $first = $api->getFeaturedByShop();
        $second = $api->getFeaturedByShop();

        self::assertSame($listings, $first);
        self::assertSame($listings, $second);
    }

    public function testGetFeaturedByShopWithLegacyIncludesQuery(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_FEATURED_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ShopListingApiInterface::KEY_LIMIT => '10',
                    ShopListingApiInterface::KEY_OFFSET => '5',
                    ShopListingApiInterface::KEY_LEGACY => 'true',
                ],
                $headers,
            )
            ->willReturn([ShopListingApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, null, $listingsTransformer);

        self::assertSame($listings, $api->getFeaturedByShop(10, 5, false, true));
    }

    public function testUpdateReturnsListing(): void
    {
        $headers = ['x-api-key' => 'key'];
        $listingData = ['listing-self'];
        $listing = self::createStub(ListingInterface::class);
        $serializedBody = ['title' => 'Renamed'];

        $request = self::createStub(UpdateListingRequestInterface::class);
        $requestSerializer = self::createMock(UpdateListingRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('patchForm')
            ->with(
                sprintf(ShopListingApiInterface::API_URL_UPDATE_SPRINTF, self::SHOP_ID, 555),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($listingData);

        $listingTransformer = self::createMock(ListingTransformerInterface::class);
        $listingTransformer->expects(self::once())->method('transform')
            ->with($listingData)
            ->willReturn($listing);

        $api = $this->buildApi($headers, $requestSender, listingTransformer: $listingTransformer, updateListingRequestSerializer: $requestSerializer);

        self::assertSame($listing, $api->update(555, $request));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('patchForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShopListingApiInterface::UNEXPECTED_RESPONSE);

        $api->update(555, self::createStub(UpdateListingRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ?ListingTransformerInterface $listingTransformer = null, ?ListingsTransformerInterface $listingsTransformer = null, ?ApiRequestSenderInterface $apiRequestSender = null, ?CreateDraftListingRequestSerializerInterface $createDraftListingRequestSerializer = null, ?UpdateListingRequestSerializerInterface $updateListingRequestSerializer = null): ShopListingApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopListingApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $listingTransformer ?? self::createStub(ListingTransformerInterface::class),
            $listingsTransformer ?? self::createStub(ListingsTransformerInterface::class),
            $createDraftListingRequestSerializer ?? self::createStub(CreateDraftListingRequestSerializerInterface::class),
            $updateListingRequestSerializer ?? self::createStub(UpdateListingRequestSerializerInterface::class),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            $credentials,
            self::SHOP_ID,
        );
    }
}
