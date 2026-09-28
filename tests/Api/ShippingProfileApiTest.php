<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\ShippingProfileApi;
use ChristianBrown\Etsy\Api\ShippingProfileApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileDestinationRequestInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileRequestInterface;
use ChristianBrown\Etsy\Model\CreateShopShippingProfileUpgradeRequestInterface;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileDestinationInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileInterface;
use ChristianBrown\Etsy\Model\ShopShippingProfileUpgradeInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileDestinationRequestInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileRequestInterface;
use ChristianBrown\Etsy\Model\UpdateShopShippingProfileUpgradeRequestInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileDestinationRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\CreateShopShippingProfileUpgradeRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileDestinationRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileRequestSerializerInterface;
use ChristianBrown\Etsy\Serializer\UpdateShopShippingProfileUpgradeRequestSerializerInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarriersTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileDestinationTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfilesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopShippingProfileUpgradeTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShippingProfileApi::class)]
#[UsesClass(ResponseCache::class)]
final class ShippingProfileApiTest extends TestCase
{
    private const int PROFILE_ID = 77;
    private const int SHOP_ID = 42;

    public function testCreateDestinationReturnsDestination(): void
    {
        $headers = ['x-api-key' => 'key'];
        $destinationData = ['destination-self'];
        $destination = self::createStub(ShopShippingProfileDestinationInterface::class);
        $serializedBody = ['primary_cost' => '5.00'];

        $request = self::createStub(CreateShopShippingProfileDestinationRequestInterface::class);
        $requestSerializer = self::createMock(CreateShopShippingProfileDestinationRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_DESTINATIONS_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($destinationData);

        $destinationTransformer = self::createMock(ShopShippingProfileDestinationTransformerInterface::class);
        $destinationTransformer->expects(self::once())->method('transform')
            ->with($destinationData)
            ->willReturn($destination);

        $api = $this->buildApi($headers, $requestSender, destinationTransformer: $destinationTransformer, createDestinationRequestSerializer: $requestSerializer);

        self::assertSame($destination, $api->createDestination(self::PROFILE_ID, $request));
    }

    public function testCreateDestinationThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->createDestination(self::PROFILE_ID, self::createStub(CreateShopShippingProfileDestinationRequestInterface::class));
    }

    public function testCreateReturnsProfile(): void
    {
        $headers = ['x-api-key' => 'key'];
        $profileData = ['profile-self'];
        $profile = self::createStub(ShopShippingProfileInterface::class);
        $serializedBody = ['title' => 'Standard'];

        $request = self::createStub(CreateShopShippingProfileRequestInterface::class);
        $requestSerializer = self::createMock(CreateShopShippingProfileRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_MULTIPLE_SPRINTF, self::SHOP_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($profileData);

        $profileTransformer = self::createMock(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->expects(self::once())->method('transform')
            ->with($profileData)
            ->willReturn($profile);

        $api = $this->buildApi($headers, $requestSender, profileTransformer: $profileTransformer, createProfileRequestSerializer: $requestSerializer);

        self::assertSame($profile, $api->create($request));
    }

    public function testCreateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->create(self::createStub(CreateShopShippingProfileRequestInterface::class));
    }

    public function testCreateUpgradeReturnsUpgrade(): void
    {
        $headers = ['x-api-key' => 'key'];
        $upgradeData = ['upgrade-self'];
        $upgrade = self::createStub(ShopShippingProfileUpgradeInterface::class);
        $serializedBody = ['upgrade_name' => 'Rush'];

        $request = self::createStub(CreateShopShippingProfileUpgradeRequestInterface::class);
        $requestSerializer = self::createMock(CreateShopShippingProfileUpgradeRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_UPGRADES_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($upgradeData);

        $upgradeTransformer = self::createMock(ShopShippingProfileUpgradeTransformerInterface::class);
        $upgradeTransformer->expects(self::once())->method('transform')
            ->with($upgradeData)
            ->willReturn($upgrade);

        $api = $this->buildApi($headers, $requestSender, upgradeTransformer: $upgradeTransformer, createUpgradeRequestSerializer: $requestSerializer);

        self::assertSame($upgrade, $api->createUpgrade(self::PROFILE_ID, $request));
    }

    public function testCreateUpgradeThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->createUpgrade(self::PROFILE_ID, self::createStub(CreateShopShippingProfileUpgradeRequestInterface::class));
    }

    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), apiRequestSender: $apiRequestSender);

        $api->delete(self::PROFILE_ID);
    }

    public function testDeleteDestinationCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_DESTINATION_SPRINTF, self::SHOP_ID, self::PROFILE_ID, 5),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), apiRequestSender: $apiRequestSender);

        $api->deleteDestination(self::PROFILE_ID, 5);
    }

    public function testDeleteUpgradeCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_UPGRADE_SPRINTF, self::SHOP_ID, self::PROFILE_ID, 9),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), apiRequestSender: $apiRequestSender);

        $api->deleteUpgrade(self::PROFILE_ID, 9);
    }

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

        $first = $api->getCarriers('US', true);
        $second = $api->getCarriers('US', true);

        self::assertSame($carriers, $first);
        self::assertSame($carriers, $second);
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

        $first = $api->getCarriers('US');
        $second = $api->getCarriers('US');

        self::assertSame($carriers, $first);
        self::assertSame($carriers, $second);
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

        $first = $api->getDestinations(self::PROFILE_ID, 25, 0, true);
        $second = $api->getDestinations(self::PROFILE_ID, 25, 0, true);

        self::assertSame($destinations, $first);
        self::assertSame($destinations, $second);
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

        $first = $api->getDestinations(self::PROFILE_ID);
        $second = $api->getDestinations(self::PROFILE_ID);

        self::assertSame($destinations, $first);
        self::assertSame($destinations, $second);
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

        $first = $api->getMultiple(true);
        $second = $api->getMultiple(true);

        self::assertSame($profiles, $first);
        self::assertSame($profiles, $second);
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

        $first = $api->getMultiple();
        $second = $api->getMultiple();

        self::assertSame($profiles, $first);
        self::assertSame($profiles, $second);
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

        $first = $api->getOneById(self::PROFILE_ID, true);
        $second = $api->getOneById(self::PROFILE_ID, true);

        self::assertSame($profile, $first);
        self::assertSame($profile, $second);
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

        $first = $api->getOneById(self::PROFILE_ID);
        $second = $api->getOneById(self::PROFILE_ID);

        self::assertSame($profile, $first);
        self::assertSame($profile, $second);
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

        $first = $api->getUpgrades(self::PROFILE_ID, true);
        $second = $api->getUpgrades(self::PROFILE_ID, true);

        self::assertSame($upgrades, $first);
        self::assertSame($upgrades, $second);
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

        $first = $api->getUpgrades(self::PROFILE_ID);
        $second = $api->getUpgrades(self::PROFILE_ID);

        self::assertSame($upgrades, $first);
        self::assertSame($upgrades, $second);
    }

    public function testUpdateDestinationReturnsDestination(): void
    {
        $headers = ['x-api-key' => 'key'];
        $destinationData = ['destination-self'];
        $destination = self::createStub(ShopShippingProfileDestinationInterface::class);
        $serializedBody = ['primary_cost' => '6.00'];

        $request = self::createStub(UpdateShopShippingProfileDestinationRequestInterface::class);
        $requestSerializer = self::createMock(UpdateShopShippingProfileDestinationRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_DESTINATION_SPRINTF, self::SHOP_ID, self::PROFILE_ID, 5),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($destinationData);

        $destinationTransformer = self::createMock(ShopShippingProfileDestinationTransformerInterface::class);
        $destinationTransformer->expects(self::once())->method('transform')
            ->with($destinationData)
            ->willReturn($destination);

        $api = $this->buildApi($headers, $requestSender, destinationTransformer: $destinationTransformer, updateDestinationRequestSerializer: $requestSerializer);

        self::assertSame($destination, $api->updateDestination(self::PROFILE_ID, 5, $request));
    }

    public function testUpdateDestinationThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->updateDestination(self::PROFILE_ID, 5, self::createStub(UpdateShopShippingProfileDestinationRequestInterface::class));
    }

    public function testUpdateReturnsProfile(): void
    {
        $headers = ['x-api-key' => 'key'];
        $profileData = ['profile-self'];
        $profile = self::createStub(ShopShippingProfileInterface::class);
        $serializedBody = ['title' => 'Renamed'];

        $request = self::createStub(UpdateShopShippingProfileRequestInterface::class);
        $requestSerializer = self::createMock(UpdateShopShippingProfileRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_ONE_SPRINTF, self::SHOP_ID, self::PROFILE_ID),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($profileData);

        $profileTransformer = self::createMock(ShopShippingProfileTransformerInterface::class);
        $profileTransformer->expects(self::once())->method('transform')
            ->with($profileData)
            ->willReturn($profile);

        $api = $this->buildApi($headers, $requestSender, profileTransformer: $profileTransformer, updateProfileRequestSerializer: $requestSerializer);

        self::assertSame($profile, $api->update(self::PROFILE_ID, $request));
    }

    public function testUpdateThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->update(self::PROFILE_ID, self::createStub(UpdateShopShippingProfileRequestInterface::class));
    }

    public function testUpdateUpgradeReturnsUpgrade(): void
    {
        $headers = ['x-api-key' => 'key'];
        $upgradeData = ['upgrade-self'];
        $upgrade = self::createStub(ShopShippingProfileUpgradeInterface::class);
        $serializedBody = ['upgrade_name' => 'Renamed'];

        $request = self::createStub(UpdateShopShippingProfileUpgradeRequestInterface::class);
        $requestSerializer = self::createMock(UpdateShopShippingProfileUpgradeRequestSerializerInterface::class);
        $requestSerializer->expects(self::once())->method('serialize')
            ->with($request)
            ->willReturn($serializedBody);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('putForm')
            ->with(
                sprintf(ShippingProfileApiInterface::API_URL_UPGRADE_SPRINTF, self::SHOP_ID, self::PROFILE_ID, 9),
                [],
                $headers,
                $serializedBody,
            )
            ->willReturn($upgradeData);

        $upgradeTransformer = self::createMock(ShopShippingProfileUpgradeTransformerInterface::class);
        $upgradeTransformer->expects(self::once())->method('transform')
            ->with($upgradeData)
            ->willReturn($upgrade);

        $api = $this->buildApi($headers, $requestSender, upgradeTransformer: $upgradeTransformer, updateUpgradeRequestSerializer: $requestSerializer);

        self::assertSame($upgrade, $api->updateUpgrade(self::PROFILE_ID, 9, $request));
    }

    public function testUpdateUpgradeThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('putForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(ShippingProfileApiInterface::UNEXPECTED_RESPONSE);

        $api->updateUpgrade(self::PROFILE_ID, 9, self::createStub(UpdateShopShippingProfileUpgradeRequestInterface::class));
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, ?ShopShippingProfileTransformerInterface $profileTransformer = null, ?ShopShippingProfilesTransformerInterface $profilesTransformer = null, ?ShopShippingProfileDestinationTransformerInterface $destinationTransformer = null, ?ShopShippingProfileDestinationsTransformerInterface $destinationsTransformer = null, ?ShopShippingProfileUpgradeTransformerInterface $upgradeTransformer = null, ?ShopShippingProfileUpgradesTransformerInterface $upgradesTransformer = null, ?ShippingCarriersTransformerInterface $carriersTransformer = null, ?ApiRequestSenderInterface $apiRequestSender = null, ?CreateShopShippingProfileRequestSerializerInterface $createProfileRequestSerializer = null, ?CreateShopShippingProfileDestinationRequestSerializerInterface $createDestinationRequestSerializer = null, ?CreateShopShippingProfileUpgradeRequestSerializerInterface $createUpgradeRequestSerializer = null, ?UpdateShopShippingProfileRequestSerializerInterface $updateProfileRequestSerializer = null, ?UpdateShopShippingProfileDestinationRequestSerializerInterface $updateDestinationRequestSerializer = null, ?UpdateShopShippingProfileUpgradeRequestSerializerInterface $updateUpgradeRequestSerializer = null): ShippingProfileApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        [$profileTransformer, $profilesTransformer, $destinationTransformer, $destinationsTransformer, $upgradeTransformer, $upgradesTransformer, $carriersTransformer] = self::resolveTransformers($profileTransformer, $profilesTransformer, $destinationTransformer, $destinationsTransformer, $upgradeTransformer, $upgradesTransformer, $carriersTransformer);
        [$createProfileRequestSerializer, $createDestinationRequestSerializer, $createUpgradeRequestSerializer, $updateProfileRequestSerializer, $updateDestinationRequestSerializer, $updateUpgradeRequestSerializer] = self::resolveSerializers($createProfileRequestSerializer, $createDestinationRequestSerializer, $createUpgradeRequestSerializer, $updateProfileRequestSerializer, $updateDestinationRequestSerializer, $updateUpgradeRequestSerializer);

        return new ShippingProfileApi(
            $requestSender,
            $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class),
            $profileTransformer,
            $profilesTransformer,
            $destinationTransformer,
            $destinationsTransformer,
            $upgradeTransformer,
            $upgradesTransformer,
            $carriersTransformer,
            $createProfileRequestSerializer,
            $createDestinationRequestSerializer,
            $createUpgradeRequestSerializer,
            $updateProfileRequestSerializer,
            $updateDestinationRequestSerializer,
            $updateUpgradeRequestSerializer,
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            new ResponseCache(),
            $credentials,
            self::SHOP_ID,
        );
    }

    /**
     * @return array{0: CreateShopShippingProfileRequestSerializerInterface, 1: CreateShopShippingProfileDestinationRequestSerializerInterface, 2: CreateShopShippingProfileUpgradeRequestSerializerInterface, 3: UpdateShopShippingProfileRequestSerializerInterface, 4: UpdateShopShippingProfileDestinationRequestSerializerInterface, 5: UpdateShopShippingProfileUpgradeRequestSerializerInterface}
     */
    private static function resolveSerializers(?CreateShopShippingProfileRequestSerializerInterface $createProfileRequestSerializer, ?CreateShopShippingProfileDestinationRequestSerializerInterface $createDestinationRequestSerializer, ?CreateShopShippingProfileUpgradeRequestSerializerInterface $createUpgradeRequestSerializer, ?UpdateShopShippingProfileRequestSerializerInterface $updateProfileRequestSerializer, ?UpdateShopShippingProfileDestinationRequestSerializerInterface $updateDestinationRequestSerializer, ?UpdateShopShippingProfileUpgradeRequestSerializerInterface $updateUpgradeRequestSerializer): array
    {
        return [
            $createProfileRequestSerializer ?? self::createStub(CreateShopShippingProfileRequestSerializerInterface::class),
            $createDestinationRequestSerializer ?? self::createStub(CreateShopShippingProfileDestinationRequestSerializerInterface::class),
            $createUpgradeRequestSerializer ?? self::createStub(CreateShopShippingProfileUpgradeRequestSerializerInterface::class),
            $updateProfileRequestSerializer ?? self::createStub(UpdateShopShippingProfileRequestSerializerInterface::class),
            $updateDestinationRequestSerializer ?? self::createStub(UpdateShopShippingProfileDestinationRequestSerializerInterface::class),
            $updateUpgradeRequestSerializer ?? self::createStub(UpdateShopShippingProfileUpgradeRequestSerializerInterface::class),
        ];
    }

    /**
     * @return array{0: ShopShippingProfileTransformerInterface, 1: ShopShippingProfilesTransformerInterface, 2: ShopShippingProfileDestinationTransformerInterface, 3: ShopShippingProfileDestinationsTransformerInterface, 4: ShopShippingProfileUpgradeTransformerInterface, 5: ShopShippingProfileUpgradesTransformerInterface, 6: ShippingCarriersTransformerInterface}
     */
    private static function resolveTransformers(?ShopShippingProfileTransformerInterface $profileTransformer, ?ShopShippingProfilesTransformerInterface $profilesTransformer, ?ShopShippingProfileDestinationTransformerInterface $destinationTransformer, ?ShopShippingProfileDestinationsTransformerInterface $destinationsTransformer, ?ShopShippingProfileUpgradeTransformerInterface $upgradeTransformer, ?ShopShippingProfileUpgradesTransformerInterface $upgradesTransformer, ?ShippingCarriersTransformerInterface $carriersTransformer): array
    {
        return [
            $profileTransformer ?? self::createStub(ShopShippingProfileTransformerInterface::class),
            $profilesTransformer ?? self::createStub(ShopShippingProfilesTransformerInterface::class),
            $destinationTransformer ?? self::createStub(ShopShippingProfileDestinationTransformerInterface::class),
            $destinationsTransformer ?? self::createStub(ShopShippingProfileDestinationsTransformerInterface::class),
            $upgradeTransformer ?? self::createStub(ShopShippingProfileUpgradeTransformerInterface::class),
            $upgradesTransformer ?? self::createStub(ShopShippingProfileUpgradesTransformerInterface::class),
            $carriersTransformer ?? self::createStub(ShippingCarriersTransformerInterface::class),
        ];
    }
}
