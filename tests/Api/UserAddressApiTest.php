<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\UserAddressApi;
use ChristianBrown\Etsy\Api\UserAddressApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Cache\ResponseCache;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddressInterface;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformerInterface;
use ChristianBrown\Etsy\Transformer\UserAddressTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(UserAddressApi::class)]
#[UsesClass(ResponseCache::class)]
final class UserAddressApiTest extends TestCase
{
    public function testDeleteCallsApiRequestSender(): void
    {
        $headers = ['x-api-key' => 'key'];

        $apiRequestSender = self::createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->expects(self::once())->method('delete')
            ->with(
                sprintf(UserAddressApiInterface::API_URL_ONE_SPRINTF, 99),
                [],
                $headers,
            );

        $api = $this->buildApi($headers, self::createStub(JsonApiRequestSenderInterface::class), self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class), $apiRequestSender);

        $api->delete(99);
    }

    public function testGetMultipleReturnsUserAddresses(): void
    {
        $resultsData = [['address-1'], ['address-2']];
        $headers = ['x-api-key' => 'key'];
        $addresses = [self::createStub(UserAddressInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                UserAddressApiInterface::API_URL_MULTIPLE,
                [
                    UserAddressApiInterface::KEY_LIMIT => '10',
                    UserAddressApiInterface::KEY_OFFSET => '5',
                ],
                $headers,
            )
            ->willReturn([UserAddressApiInterface::KEY_RESULTS => $resultsData]);

        $addressesTransformer = self::createMock(UserAddressesTransformerInterface::class);
        $addressesTransformer->expects(self::once())->method('transform')
            ->with($resultsData)
            ->willReturn($addresses);

        $api = $this->buildApi($headers, $requestSender, self::createStub(UserAddressTransformerInterface::class), $addressesTransformer);

        self::assertSame($addresses, $api->getMultiple(10, 5));
    }

    public function testGetMultipleSkipCacheRefetches(): void
    {
        $addresses = [self::createStub(UserAddressInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn([UserAddressApiInterface::KEY_RESULTS => [['address-1']]]);

        $addressesTransformer = self::createStub(UserAddressesTransformerInterface::class);
        $addressesTransformer->method('transform')->willReturn($addresses);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), $addressesTransformer);

        $first = $api->getMultiple(25, 0, true);
        $second = $api->getMultiple(25, 0, true);

        self::assertSame($addresses, $first);
        self::assertSame($addresses, $second);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([UserAddressApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressApiInterface::UNEXPECTED_RESPONSE_SPRINTF, UserAddressApiInterface::KEY_RESULTS));

        $api->getMultiple(25, 0, true);
    }

    public function testGetMultipleSkipCacheThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([UserAddressApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressApiInterface::UNEXPECTED_RESPONSE_SPRINTF, UserAddressApiInterface::KEY_RESULTS));

        $api->getMultiple(25, 0, true);
    }

    public function testGetMultipleThrowsWhenResultsMissing(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([UserAddressApiInterface::KEY_RESULTS => []]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressApiInterface::UNEXPECTED_RESPONSE_SPRINTF, UserAddressApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleThrowsWhenResultsNotArray(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([UserAddressApiInterface::KEY_RESULTS => 'not-an-array']);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressApiInterface::UNEXPECTED_RESPONSE_SPRINTF, UserAddressApiInterface::KEY_RESULTS));

        $api->getMultiple();
    }

    public function testGetMultipleUsesCacheOnSecondCall(): void
    {
        $addresses = [self::createStub(UserAddressInterface::class)];

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn([UserAddressApiInterface::KEY_RESULTS => [['address-1']]]);

        $addressesTransformer = self::createStub(UserAddressesTransformerInterface::class);
        $addressesTransformer->method('transform')->willReturn($addresses);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), $addressesTransformer);

        $first = $api->getMultiple();
        $second = $api->getMultiple();

        self::assertSame($addresses, $first);
        self::assertSame($addresses, $second);
    }

    public function testGetOneByIdReturnsUserAddress(): void
    {
        $addressData = ['address-99'];
        $headers = ['x-api-key' => 'key'];
        $address = self::createStub(UserAddressInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(UserAddressApiInterface::API_URL_ONE_SPRINTF, 99),
                [],
                $headers,
            )
            ->willReturn($addressData);

        $addressTransformer = self::createMock(UserAddressTransformerInterface::class);
        $addressTransformer->expects(self::once())->method('transform')
            ->with($addressData)
            ->willReturn($address);

        $api = $this->buildApi($headers, $requestSender, $addressTransformer, self::createStub(UserAddressesTransformerInterface::class));

        self::assertSame($address, $api->getOneById(99));
    }

    public function testGetOneByIdSkipCacheRefetches(): void
    {
        $address = self::createStub(UserAddressInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['address-99']);

        $addressTransformer = self::createStub(UserAddressTransformerInterface::class);
        $addressTransformer->method('transform')->willReturn($address);

        $api = $this->buildApi([], $requestSender, $addressTransformer, self::createStub(UserAddressesTransformerInterface::class));

        $first = $api->getOneById(99, true);
        $second = $api->getOneById(99, true);

        self::assertSame($address, $first);
        self::assertSame($address, $second);
    }

    public function testGetOneByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserAddressApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99, true);
    }

    public function testGetOneByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserAddressTransformerInterface::class), self::createStub(UserAddressesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserAddressApiInterface::UNEXPECTED_RESPONSE);

        $api->getOneById(99);
    }

    public function testGetOneByIdUsesCacheOnSecondCall(): void
    {
        $address = self::createStub(UserAddressInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['address-99']);

        $addressTransformer = self::createStub(UserAddressTransformerInterface::class);
        $addressTransformer->method('transform')->willReturn($address);

        $api = $this->buildApi([], $requestSender, $addressTransformer, self::createStub(UserAddressesTransformerInterface::class));

        $first = $api->getOneById(99);
        $second = $api->getOneById(99);

        self::assertSame($address, $first);
        self::assertSame($address, $second);
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, UserAddressTransformerInterface $userAddressTransformer, UserAddressesTransformerInterface $userAddressesTransformer, ?ApiRequestSenderInterface $apiRequestSender = null): UserAddressApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new UserAddressApi($requestSender, $apiRequestSender ?? self::createStub(ApiRequestSenderInterface::class), $userAddressTransformer, $userAddressesTransformer, new ResponseCache(), new ResponseCache(), $credentials);
    }
}
