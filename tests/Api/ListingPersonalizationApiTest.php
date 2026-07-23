<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ListingPersonalizationApi;
use ChristianBrown\Etsy\Api\ListingPersonalizationApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;
use ChristianBrown\Etsy\Transformer\ListingPersonalizationTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ListingPersonalizationApi::class)]
final class ListingPersonalizationApiTest extends TestCase
{
    private const int LISTING_ID = 7;

    public function testGetReturnsPersonalization(): void
    {
        $personalizationData = ['personalization'];
        $headers = ['x-api-key' => 'key'];
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ListingPersonalizationApiInterface::API_URL_ONE_SPRINTF, self::LISTING_ID),
                [],
                $headers,
            )
            ->willReturn($personalizationData);

        $personalizationTransformer = self::createMock(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->expects(self::once())->method('transform')
            ->with($personalizationData)
            ->willReturn($personalization);

        $api = $this->buildApi($headers, $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID));
    }

    public function testGetSkipCacheRefetches(): void
    {
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['personalization']);

        $personalizationTransformer = self::createStub(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->method('transform')->willReturn($personalization);

        $api = $this->buildApi([], $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID, true));
        self::assertSame($personalization, $api->get(self::LISTING_ID, true));
    }

    public function testGetSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPersonalizationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPersonalizationApiInterface::UNEXPECTED_RESPONSE);

        $api->get(self::LISTING_ID, true);
    }

    public function testGetThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(ListingPersonalizationTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ListingPersonalizationApiInterface::UNEXPECTED_RESPONSE);

        $api->get(self::LISTING_ID);
    }

    public function testGetUsesCacheOnSecondCall(): void
    {
        $personalization = self::createStub(ListingPersonalizationInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['personalization']);

        $personalizationTransformer = self::createStub(ListingPersonalizationTransformerInterface::class);
        $personalizationTransformer->method('transform')->willReturn($personalization);

        $api = $this->buildApi([], $requestSender, $personalizationTransformer);

        self::assertSame($personalization, $api->get(self::LISTING_ID));
        self::assertSame($personalization, $api->get(self::LISTING_ID));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ListingPersonalizationTransformerInterface $personalizationTransformer): ListingPersonalizationApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ListingPersonalizationApi($requestSender, $personalizationTransformer, $credentials);
    }
}
