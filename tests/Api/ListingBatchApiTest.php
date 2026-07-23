<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingBatchApi;
use ChristianBrown\Etsy\Api\ListingBatchApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;
use ChristianBrown\Etsy\Transformer\ListingsWithAssociationsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingBatchApi::class)]
final class ListingBatchApiTest extends TestCase
{
    public function testGetInventoryByListingIdsReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ListingBatchApiInterface::API_URL_INVENTORY,
                [
                    ListingBatchApiInterface::KEY_LISTING_IDS => '1,2,3',
                ],
                $headers,
            )
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getInventoryByListingIds([1, 2, 3]));
    }

    public function testGetInventoryByListingIdsSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getInventoryByListingIds([1, 2, 3], true));
        self::assertSame($listings, $api->getInventoryByListingIds([1, 2, 3], true));
    }

    public function testGetInventoryByListingIdsSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingBatchApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingBatchApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingBatchApiInterface::KEY_RESULTS));

        $api->getInventoryByListingIds([1, 2, 3], true);
    }

    public function testGetInventoryByListingIdsThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ListingBatchApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ListingBatchApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ListingBatchApiInterface::KEY_RESULTS));

        $api->getInventoryByListingIds([1, 2, 3]);
    }

    public function testGetInventoryByListingIdsUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getInventoryByListingIds([1, 2, 3]));
        self::assertSame($listings, $api->getInventoryByListingIds([1, 2, 3]));
    }

    public function testGetShippingByListingIdsReturnsListings(): void
    {
        $resultsData = [['listing-1']];
        $headers = ['x-api-key' => 'key'];
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ListingBatchApiInterface::API_URL_SHIPPING,
                [
                    ListingBatchApiInterface::KEY_LISTING_IDS => '1,2,3',
                ],
                $headers,
            )
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => $resultsData]);

        $listingsTransformer = self::createMock(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($listings);

        $api = $this->buildApi($headers, $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getShippingByListingIds([1, 2, 3]));
    }

    public function testGetShippingByListingIdsSkipCacheRefetches(): void
    {
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getShippingByListingIds([1, 2, 3], true));
        self::assertSame($listings, $api->getShippingByListingIds([1, 2, 3], true));
    }

    public function testGetShippingByListingIdsUsesCacheOnSecondCall(): void
    {
        $listings = [self::createStub(ListingWithAssociationsInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ListingBatchApiInterface::KEY_RESULTS => [['listing-1']]]);

        $listingsTransformer = self::createStub(ListingsWithAssociationsTransformerInterface::class);
        $listingsTransformer->method('transform')->willReturn($listings);

        $api = $this->buildApi([], $requestSender, $listingsTransformer);

        self::assertSame($listings, $api->getShippingByListingIds([1, 2, 3]));
        self::assertSame($listings, $api->getShippingByListingIds([1, 2, 3]));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ?ListingsWithAssociationsTransformerInterface $listingsTransformer = null): ListingBatchApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingBatchApi(
            $requestSender,
            $listingsTransformer ?? self::createStub(ListingsWithAssociationsTransformerInterface::class),
            $credentials,
        );
    }
}
