<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ReviewApi;
use ChristianBrown\Etsy\Api\ReviewApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReviewInterface;
use ChristianBrown\Etsy\Transformer\ReviewsTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ReviewApi::class)]
final class ReviewApiTest extends TestCase
{
    private const int LISTING_ID = 77;
    private const int SHOP_ID = 42;

    public function testGetByListingReturnsReviews(): void
    {
        $resultsData = [['review-1'], ['review-2']];
        $headers = ['x-api-key' => 'key'];
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ReviewApiInterface::API_URL_BY_LISTING_SPRINTF, self::LISTING_ID),
                [
                    ReviewApiInterface::KEY_LIMIT => '10',
                    ReviewApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([ReviewApiInterface::KEY_RESULTS => $resultsData]);

        $reviewsTransformer = self::createMock(ReviewsTransformerInterface::class);
        $reviewsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($reviews);

        $api = $this->buildApi($headers, $requestSender, $reviewsTransformer);

        self::assertSame($reviews, $api->getByListing(self::LISTING_ID, 10, 5));
    }

    public function testGetByListingSkipCacheRefetches(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        $first = $api->getByListing(self::LISTING_ID, 25, 0, true);
        $second = $api->getByListing(self::LISTING_ID, 25, 0, true);

        self::assertSame($reviews, $first);
        self::assertSame($reviews, $second);
    }

    public function testGetByListingSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByListing(self::LISTING_ID, 25, 0, true);
    }

    public function testGetByListingSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByListing(self::LISTING_ID, 25, 0, true);
    }

    public function testGetByListingThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByListing(self::LISTING_ID);
    }

    public function testGetByListingThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByListing(self::LISTING_ID);
    }

    public function testGetByListingUsesCacheOnSecondCall(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        $first = $api->getByListing(self::LISTING_ID);
        $second = $api->getByListing(self::LISTING_ID);

        self::assertSame($reviews, $first);
        self::assertSame($reviews, $second);
    }

    public function testGetByShopReturnsReviewsWithMinAndMaxCreated(): void
    {
        $resultsData = [['review-1'], ['review-2']];
        $headers = ['x-api-key' => 'key'];
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ReviewApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ReviewApiInterface::KEY_LIMIT => '10',
                    ReviewApiInterface::KEY_OFFSET => '5',
                    ReviewApiInterface::KEY_MIN_CREATED => '100',
                    ReviewApiInterface::KEY_MAX_CREATED => '200',
                ],
                $headers,
            )
            ->willReturn([ReviewApiInterface::KEY_RESULTS => $resultsData]);

        $reviewsTransformer = self::createMock(ReviewsTransformerInterface::class);
        $reviewsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($reviews);

        $api = $this->buildApi($headers, $requestSender, $reviewsTransformer);

        self::assertSame($reviews, $api->getByShop(100, 200, 10, 5));
    }

    public function testGetByShopSkipCacheRefetches(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        $first = $api->getByShop(null, null, 25, 0, true);
        $second = $api->getByShop(null, null, 25, 0, true);

        self::assertSame($reviews, $first);
        self::assertSame($reviews, $second);
    }

    public function testGetByShopSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByShop(null, null, 25, 0, true);
    }

    public function testGetByShopSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByShop(null, null, 25, 0, true);
    }

    public function testGetByShopThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByShop();
    }

    public function testGetByShopThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ReviewApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ReviewsTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ReviewApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ReviewApiInterface::KEY_RESULTS));

        $api->getByShop();
    }

    public function testGetByShopUsesCacheOnSecondCall(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        $first = $api->getByShop();
        $second = $api->getByShop();

        self::assertSame($reviews, $first);
        self::assertSame($reviews, $second);
    }

    public function testGetByShopWithMaxCreatedOnly(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ReviewApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ReviewApiInterface::KEY_LIMIT => '25',
                    ReviewApiInterface::KEY_OFFSET => '0',
                    ReviewApiInterface::KEY_MAX_CREATED => '200',
                ],
                [],
            )
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        self::assertSame($reviews, $api->getByShop(null, 200));
    }

    public function testGetByShopWithMinCreatedOnly(): void
    {
        $reviews = [self::createStub(ReviewInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ReviewApiInterface::API_URL_BY_SHOP_SPRINTF, self::SHOP_ID),
                [
                    ReviewApiInterface::KEY_LIMIT => '25',
                    ReviewApiInterface::KEY_OFFSET => '0',
                    ReviewApiInterface::KEY_MIN_CREATED => '100',
                ],
                [],
            )
            ->willReturn([ReviewApiInterface::KEY_RESULTS => [['review-1']]]);

        $reviewsTransformer = self::createStub(ReviewsTransformerInterface::class);
        $reviewsTransformer->method('transform')->willReturn($reviews);

        $api = $this->buildApi([], $requestSender, $reviewsTransformer);

        self::assertSame($reviews, $api->getByShop(100));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ReviewsTransformerInterface $reviewsTransformer): ReviewApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ReviewApi($requestSender, $reviewsTransformer, $credentials, self::SHOP_ID);
    }
}
