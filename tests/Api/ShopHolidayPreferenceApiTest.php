<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApi;
use ChristianBrown\Etsy\Api\ShopHolidayPreferenceApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Model\ShopHolidayPreferenceInterface;
use ChristianBrown\Etsy\Transformer\ShopHolidayPreferencesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopHolidayPreferenceApi::class)]
final class ShopHolidayPreferenceApiTest extends TestCase
{
    private const int SHOP_ID = 42;

    public function testGetMultipleReturnsEmpty(): void
    {
        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')->willReturn([]);

        $preferencesTransformer = self::createMock(ShopHolidayPreferencesTransformerInterface::class);
        $preferencesTransformer->expects(self::once())->method('transform')
            ->with([])
            ->willReturn([]);

        $api = $this->buildApi([], $requestSender, $preferencesTransformer);

        self::assertSame([], $api->getMultiple());
    }

    public function testGetMultipleReturnsPreferences(): void
    {
        $responseData = [['preference-1'], ['preference-2']];
        $headers = ['x-api-key' => 'key'];
        $preferences = [self::createStub(ShopHolidayPreferenceInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShopHolidayPreferenceApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn($responseData);

        $preferencesTransformer = self::createMock(ShopHolidayPreferencesTransformerInterface::class);
        $preferencesTransformer->expects(self::once())->method('transform')
            ->with($responseData)
            ->willReturn($preferences);

        $api = $this->buildApi($headers, $requestSender, $preferencesTransformer);

        self::assertSame($preferences, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $preferences = [self::createStub(ShopHolidayPreferenceInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([['preference-1']]);

        $preferencesTransformer = self::createStub(ShopHolidayPreferencesTransformerInterface::class);
        $preferencesTransformer->method('transform')->willReturn($preferences);

        $api = $this->buildApi([], $requestSender, $preferencesTransformer);

        self::assertSame($preferences, $api->getMultiple(true));
        self::assertSame($preferences, $api->getMultiple(true));
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $preferences = [self::createStub(ShopHolidayPreferenceInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([['preference-1']]);

        $preferencesTransformer = self::createStub(ShopHolidayPreferencesTransformerInterface::class);
        $preferencesTransformer->method('transform')->willReturn($preferences);

        $api = $this->buildApi([], $requestSender, $preferencesTransformer);

        self::assertSame($preferences, $api->getMultiple());
        self::assertSame($preferences, $api->getMultiple());
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ShopHolidayPreferencesTransformerInterface $shopHolidayPreferencesTransformer): ShopHolidayPreferenceApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShopHolidayPreferenceApi($requestSender, $shopHolidayPreferencesTransformer, $credentials, self::SHOP_ID);
    }
}
