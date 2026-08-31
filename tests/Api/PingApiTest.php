<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\PingApi;
use ChristianBrown\Etsy\Api\PingApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PingInterface;
use ChristianBrown\Etsy\Model\ScopesInterface;
use ChristianBrown\Etsy\Transformer\PingTransformerInterface;
use ChristianBrown\Etsy\Transformer\ScopesTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PingApi::class)]
final class PingApiTest extends TestCase
{
    public function testGetScopesReturnsScopes(): void
    {
        $scopesData = ['scopes' => ['listings_r']];
        $headers = ['x-api-key' => 'key'];
        $scopes = self::createStub(ScopesInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('postForm')
            ->with(PingApiInterface::API_URL_SCOPES, [], $headers, [PingApiInterface::KEY_TOKEN => 'a-token'])
            ->willReturn($scopesData);

        $scopesTransformer = self::createMock(ScopesTransformerInterface::class);
        $scopesTransformer->expects(self::once())->method('transform')
            ->with($scopesData)
            ->willReturn($scopes);

        $api = $this->buildApi($headers, $requestSender, self::createStub(PingTransformerInterface::class), $scopesTransformer);

        self::assertSame($scopes, $api->getScopes('a-token'));
    }

    public function testGetScopesThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('postForm')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(PingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(PingApiInterface::UNEXPECTED_RESPONSE);

        $api->getScopes('a-token');
    }

    public function testPingReturnsPing(): void
    {
        $pingData = ['ping'];
        $headers = ['x-api-key' => 'key'];
        $ping = self::createStub(PingInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(PingApiInterface::API_URL, [], $headers)
            ->willReturn($pingData);

        $pingTransformer = self::createMock(PingTransformerInterface::class);
        $pingTransformer->expects(self::once())->method('transform')
            ->with($pingData)
            ->willReturn($ping);

        $api = $this->buildApi($headers, $requestSender, $pingTransformer);

        self::assertSame($ping, $api->ping());
    }

    public function testPingThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(PingTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(PingApiInterface::UNEXPECTED_RESPONSE);

        $api->ping();
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, PingTransformerInterface $pingTransformer, ?ScopesTransformerInterface $scopesTransformer = null): PingApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new PingApi($requestSender, $pingTransformer, $scopesTransformer ?? self::createStub(ScopesTransformerInterface::class), $credentials);
    }
}
