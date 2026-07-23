<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShippingProfileApi;
use ChristianBrown\Etsy\Api\ShippingProfileApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShippingProfileApi::class)]
final class ShippingProfileApiTest extends TestCase
{
    private const int PROFILE_ID = 77;
    private const int SHOP_ID = 42;

    public function testGetCarriersReturnsCarriers(): void
    {
        $resultsData = [['carrier-1']];
        $headers = ['x-api-key' => 'key'];
        $carriers = [self::createStub(ShippingCarrierInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                ShippingProfileApiInterface::API_URL_CARRIERS,
                [ShippingProfileApiInterface::KEY_ORIGIN_COUNTRY_ISO => 'US'],
                $headers,
            )
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => $resultsData]);

        $carriersTransformer = self::createMock(ShippingCarriersTransformerInterface::class);
        $carriersTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($carriers);

        $api = $this->buildApi($headers, $requestSender, carriersTransformer: $carriersTransformer);

        self::assertSame($carriers, $api->getCarriers('US'));
    }

    public function testGetCarriersSkipCacheRefetches(): void
    {
        $carriers = [self::createStub(ShippingCarrierInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['carrier-1']]]);

        $carriersTransformer = self::createStub(ShippingCarriersTransformerInterface::class);
        $carriersTransformer->method('transform')->willReturn($carriers);

        $api = $this->buildApi([], $requestSender, carriersTransformer: $carriersTransformer);

        self::assertSame($carriers, $api->getCarriers('US', true));
        self::assertSame($carriers, $api->getCarriers('US', true));
    }

    public function testGetCarriersSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getCarriers('US', true);
    }

    public function testGetCarriersSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getCarriers('US', true);
    }

    public function testGetCarriersThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getCarriers('US');
    }

    public function testGetCarriersThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getCarriers('US');
    }

    public function testGetCarriersUsesCacheOnSecondCall(): void
    {
        $carriers = [self::createStub(ShippingCarrierInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['carrier-1']]]);

        $carriersTransformer = self::createStub(ShippingCarriersTransformerInterface::class);
        $carriersTransformer->method('transform')->willReturn($carriers);

        $api = $this->buildApi([], $requestSender, carriersTransformer: $carriersTransformer);

        self::assertSame($carriers, $api->getCarriers('US'));
        self::assertSame($carriers, $api->getCarriers('US'));
    }

    public function testGetDestinationsReturnsDestinations(): void
    {
        $resultsData = [['destination-1']];
        $headers = ['x-api-key' => 'key'];
        $destinations = [self::createStub(ShopShippingProfileDestinationInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_DESTINATIONS_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [
                    ShippingProfileApiInterface::KEY_LIMIT => '25',
                    ShippingProfileApiInterface::KEY_OFFSET => '0',
                ],
                $headers,
            )
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => $resultsData]);

        $destinationsTransformer = self::createMock(ShopShippingProfileDestinationsTransformerInterface::class);
        $destinationsTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($destinations);

        $api = $this->buildApi($headers, $requestSender, destinationsTransformer: $destinationsTransformer);

        self::assertSame($destinations, $api->getDestinations(self::PROFILE_ID));
    }

    public function testGetDestinationsSkipCacheRefetches(): void
    {
        $destinations = [self::createStub(ShopShippingProfileDestinationInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['destination-1']]]);

        $destinationsTransformer = self::createStub(ShopShippingProfileDestinationsTransformerInterface::class);
        $destinationsTransformer->method('transform')->willReturn($destinations);

        $api = $this->buildApi([], $requestSender, destinationsTransformer: $destinationsTransformer);

        self::assertSame($destinations, $api->getDestinations(self::PROFILE_ID, 25, 0, true));
        self::assertSame($destinations, $api->getDestinations(self::PROFILE_ID, 25, 0, true));
    }

    public function testGetDestinationsSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getDestinations(self::PROFILE_ID, 25, 0, true);
    }

    public function testGetDestinationsSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getDestinations(self::PROFILE_ID, 25, 0, true);
    }

    public function testGetDestinationsThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getDestinations(self::PROFILE_ID);
    }

    public function testGetDestinationsThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getDestinations(self::PROFILE_ID);
    }

    public function testGetDestinationsUsesCacheOnSecondCall(): void
    {
        $destinations = [self::createStub(ShopShippingProfileDestinationInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['destination-1']]]);

        $destinationsTransformer = self::createStub(ShopShippingProfileDestinationsTransformerInterface::class);
        $destinationsTransformer->method('transform')->willReturn($destinations);

        $api = $this->buildApi([], $requestSender, destinationsTransformer: $destinationsTransformer);

        self::assertSame($destinations, $api->getDestinations(self::PROFILE_ID));
        self::assertSame($destinations, $api->getDestinations(self::PROFILE_ID));
    }

    public function testGetMultipleReturnsProfiles(): void
    {
        $resultsData = [['profile-1'], ['profile-2']];
        $headers = ['x-api-key' => 'key'];
        $profiles = [self::createStub(ShopShippingProfileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
            )
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => $resultsData]);

        $profilesTransformer = self::createMock(ShopShippingProfilesTransformerInterface::class);
        $profilesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($profiles);

        $api = $this->buildApi($headers, $requestSender, profilesTransformer: $profilesTransformer);

        self::assertSame($profiles, $api->getMultiple());
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $profiles = [self::createStub(ShopShippingProfileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['profile-1']]]);

        $profilesTransformer = self::createStub(ShopShippingProfilesTransformerInterface::class);
        $profilesTransformer->method('transform')->willReturn($profiles);

        $api = $this->buildApi([], $requestSender, profilesTransformer: $profilesTransformer);

        self::assertSame($profiles, $api->getMultiple(true));
        self::assertSame($profiles, $api->getMultiple(true));
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getMultiple(true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $profiles = [self::createStub(ShopShippingProfileInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['profile-1']]]);

        $profilesTransformer = self::createStub(ShopShippingProfilesTransformerInterface::class);
        $profilesTransformer->method('transform')->willReturn($profiles);

        $api = $this->buildApi([], $requestSender, profilesTransformer: $profilesTransformer);

        self::assertSame($profiles, $api->getMultiple());
        self::assertSame($profiles, $api->getMultiple());
    }

    public function testGetOneByIdReturnsProfile(): void
    {
        $profileData = ['profile-self'];
        $headers = ['x-api-key' => 'key'];
        $profile = self::createStub(ShopShippingProfileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
            )
            ->willReturn($profileData);

        $profileTransformer = self::createMock(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->expects(self::once())->method('transform')
            ->with($profileData)
            ->willReturn($profile);

        $api = $this->buildApi($headers, $requestSender, profileTransformer: $profileTransformer);

        self::assertSame($profile, $api->getOneById(self::PROFILE_ID));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $profile = self::createStub(ShopShippingProfileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')->willReturn(['profile-self']);

        $profileTransformer = self::createStub(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->method('transform')->willReturn($profile);

        $api = $this->buildApi([], $requestSender, profileTransformer: $profileTransformer);

        self::assertSame($profile, $api->getOneById(self::PROFILE_ID, true));
        self::assertSame($profile, $api->getOneById(self::PROFILE_ID, true));
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::PROFILE_ID, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(self::PROFILE_ID);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $profile = self::createStub(ShopShippingProfileInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')->willReturn(['profile-self']);

        $profileTransformer = self::createStub(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->method('transform')->willReturn($profile);

        $api = $this->buildApi([], $requestSender, profileTransformer: $profileTransformer);

        self::assertSame($profile, $api->getOneById(self::PROFILE_ID));
        self::assertSame($profile, $api->getOneById(self::PROFILE_ID));
    }

    public function testGetUpgradesReturnsUpgrades(): void
    {
        $resultsData = [['upgrade-1']];
        $headers = ['x-api-key' => 'key'];
        $upgrades = [self::createStub(ShopShippingProfileUpgradeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_UPGRADES_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
            )
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => $resultsData]);

        $upgradesTransformer = self::createMock(ShopShippingProfileUpgradesTransformerInterface::class);
        $upgradesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($upgrades);

        $api = $this->buildApi($headers, $requestSender, upgradesTransformer: $upgradesTransformer);

        self::assertSame($upgrades, $api->getUpgrades(self::PROFILE_ID));
    }

    public function testGetUpgradesSkipCacheRefetches(): void
    {
        $upgrades = [self::createStub(ShopShippingProfileUpgradeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['upgrade-1']]]);

        $upgradesTransformer = self::createStub(ShopShippingProfileUpgradesTransformerInterface::class);
        $upgradesTransformer->method('transform')->willReturn($upgrades);

        $api = $this->buildApi([], $requestSender, upgradesTransformer: $upgradesTransformer);

        self::assertSame($upgrades, $api->getUpgrades(self::PROFILE_ID, true));
        self::assertSame($upgrades, $api->getUpgrades(self::PROFILE_ID, true));
    }

    public function testGetUpgradesSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getUpgrades(self::PROFILE_ID, true);
    }

    public function testGetUpgradesSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getUpgrades(self::PROFILE_ID, true);
    }

    public function testGetUpgradesThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getUpgrades(self::PROFILE_ID);
    }

    public function testGetUpgradesThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([ShippingProfileApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingProfileApiInterface::UNEXPECTED_RESPONSE_SPRINTF, ShippingProfileApiInterface::KEY_RESULTS));

        $api->getUpgrades(self::PROFILE_ID);
    }

    public function testGetUpgradesUsesCacheOnSecondCall(): void
    {
        $upgrades = [self::createStub(ShopShippingProfileUpgradeInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([ShippingProfileApiInterface::KEY_RESULTS => [['upgrade-1']]]);

        $upgradesTransformer = self::createStub(ShopShippingProfileUpgradesTransformerInterface::class);
        $upgradesTransformer->method('transform')->willReturn($upgrades);

        $api = $this->buildApi([], $requestSender, upgradesTransformer: $upgradesTransformer);

        self::assertSame($upgrades, $api->getUpgrades(self::PROFILE_ID));
        self::assertSame($upgrades, $api->getUpgrades(self::PROFILE_ID));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ?ShopShippingProfileTransformerInterface $profileTransformer = null, ?ShopShippingProfilesTransformerInterface $profilesTransformer = null, ?ShopShippingProfileDestinationsTransformerInterface $destinationsTransformer = null, ?ShopShippingProfileUpgradesTransformerInterface $upgradesTransformer = null, ?ShippingCarriersTransformerInterface $carriersTransformer = null): ShippingProfileApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new ShippingProfileApi(
            $requestSender,
            $profileTransformer ?? self::createStub(ShopShippingProfileTransformerInterface::class),
            $profilesTransformer ?? self::createStub(ShopShippingProfilesTransformerInterface::class),
            $destinationsTransformer ?? self::createStub(ShopShippingProfileDestinationsTransformerInterface::class),
            $upgradesTransformer ?? self::createStub(ShopShippingProfileUpgradesTransformerInterface::class),
            $carriersTransformer ?? self::createStub(ShippingCarriersTransformerInterface::class),
            $credentials,
            self::SHOP_ID,
        );
    }
}
