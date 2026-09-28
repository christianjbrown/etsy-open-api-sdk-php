<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApi;
use ChristianBrown\Etsy\Api\ShopProductionPartnerApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;
use ChristianBrown\Etsy\Transformer\ShopProductionPartnersTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopProductionPartnerApi::class)]
#[UsesClass(ResponseCache::class)]
final class ShopProductionPartnerApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsPartners(): void
    {
        $resultsData = [['partner-1'], ['partner-2']];
        $headers = ['x-api-key' => 'key'];
        $partners = [self::createStub(ShopProductionPartnerInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopProductionPartnerApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => $resultsData]);

        $partnersTransformer = self::createMock(ShopProductionPartnersTransformerInterface::class);
        $partnersTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($partners);

        $api = $this->buildApi($headers, $requestSender, $partnersTransformer);

        self::assertSame($partners, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $partners = [self::createStub(ShopProductionPartnerInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => [['partner-1']]]);

        $partnersTransformer = self::createStub(ShopProductionPartnersTransformerInterface::class);
        $partnersTransformer->method('transform')->willReturn($partners);

        $api = $this->buildApi([], $requestSender, $partnersTransformer);

        $first = $api->getMultiple(true);
        $second = $api->getMultiple(true);

        self::assertSame($partners, $first);
        self::assertSame($partners, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopProductionPartnersTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnerApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopProductionPartnerApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopProductionPartnersTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnerApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopProductionPartnerApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopProductionPartnersTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnerApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopProductionPartnerApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(ShopProductionPartnersTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopProductionPartnerApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShopProductionPartnerApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $partners = [self::createStub(ShopProductionPartnerInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShopProductionPartnerApiInterface::KEY_RESULTS => [['partner-1']]]);

        $partnersTransformer = self::createStub(ShopProductionPartnersTransformerInterface::class);
        $partnersTransformer->method('transform')->willReturn($partners);

        $api = $this->buildApi([], $requestSender, $partnersTransformer);

        $first = $api->getMultiple();
        $second = $api->getMultiple();

        self::assertSame($partners, $first);
        self::assertSame($partners, $second);
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer): ShopProductionPartnerApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopProductionPartnerApi($requestSender, $shopProductionPartnersTransformer, new ResponseCache(), $credentials, self::SHOP_ID);
    }
}
