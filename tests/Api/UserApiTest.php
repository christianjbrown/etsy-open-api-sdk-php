<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Api;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Api\UserApi;
use ChristianBrown\Etsy\Api\UserApiInterface;
use ChristianBrown\Etsy\Auth\CredentialsInterface;
use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserInterface;
use ChristianBrown\Etsy\Transformer\UserTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(UserApi::class)]
final class UserApiTest extends TestCase
{
    public function testGetByIdReturnsUser(): void
    {
        $userData = ['user-88'];
        $headers = ['x-api-key' => 'key'];
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(
                sprintf(UserApiInterface::API_URL_ONE_SPRINTF, 88),
                [],
                $headers,
            )
            ->willReturn($userData);

        $userTransformer = self::createMock(UserTransformerInterface::class);
        $userTransformer->expects(self::once())->method('transform')
            ->with($userData)
            ->willReturn($user);

        $api = $this->buildApi($headers, $requestSender, $userTransformer);

        self::assertSame($user, $api->getById(88));
    }

    public function testGetByIdSkipCacheRefetches(): void
    {
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['user-88']);

        $userTransformer = self::createStub(UserTransformerInterface::class);
        $userTransformer->method('transform')->willReturn($user);

        $api = $this->buildApi([], $requestSender, $userTransformer);

        self::assertSame($user, $api->getById(88, true));
        self::assertSame($user, $api->getById(88, true));
    }

    public function testGetByIdSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserApiInterface::UNEXPECTED_RESPONSE);

        $api->getById(88, true);
    }

    public function testGetByIdThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserApiInterface::UNEXPECTED_RESPONSE);

        $api->getById(88);
    }

    public function testGetByIdUsesCacheOnSecondCall(): void
    {
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['user-88']);

        $userTransformer = self::createStub(UserTransformerInterface::class);
        $userTransformer->method('transform')->willReturn($user);

        $api = $this->buildApi([], $requestSender, $userTransformer);

        self::assertSame($user, $api->getById(88));
        self::assertSame($user, $api->getById(88));
    }

    public function testGetMeReturnsUser(): void
    {
        $userData = ['user-me'];
        $headers = ['x-api-key' => 'key'];
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->with(UserApiInterface::API_URL_ME, [], $headers)
            ->willReturn($userData);

        $userTransformer = self::createMock(UserTransformerInterface::class);
        $userTransformer->expects(self::once())->method('transform')
            ->with($userData)
            ->willReturn($user);

        $api = $this->buildApi($headers, $requestSender, $userTransformer);

        self::assertSame($user, $api->getMe());
    }

    public function testGetMeSkipCacheRefetches(): void
    {
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::exactly(2))->method('get')
            ->willReturn(['user-me']);

        $userTransformer = self::createStub(UserTransformerInterface::class);
        $userTransformer->method('transform')->willReturn($user);

        $api = $this->buildApi([], $requestSender, $userTransformer);

        self::assertSame($user, $api->getMe(true));
        self::assertSame($user, $api->getMe(true));
    }

    public function testGetMeSkipCacheThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserApiInterface::UNEXPECTED_RESPONSE);

        $api->getMe(true);
    }

    public function testGetMeThrowsWhenEmpty(): void
    {
        $requestSender = self::createStub(JsonApiRequestSenderInterface::class);
        $requestSender->method('get')->willReturn([]);

        $api = $this->buildApi([], $requestSender, self::createStub(UserTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(UserApiInterface::UNEXPECTED_RESPONSE);

        $api->getMe();
    }

    public function testGetMeUsesCacheOnSecondCall(): void
    {
        $user = self::createStub(UserInterface::class);

        $requestSender = self::createMock(JsonApiRequestSenderInterface::class);
        $requestSender->expects(self::once())->method('get')
            ->willReturn(['user-me']);

        $userTransformer = self::createStub(UserTransformerInterface::class);
        $userTransformer->method('transform')->willReturn($user);

        $api = $this->buildApi([], $requestSender, $userTransformer);

        self::assertSame($user, $api->getMe());
        self::assertSame($user, $api->getMe());
    }

    /**
     * @param array<string, string> $headers
     */
    private function buildApi(array $headers, JsonApiRequestSenderInterface $requestSender, UserTransformerInterface $userTransformer): UserApi
    {
        $credentials = self::createStub(CredentialsInterface::class);
        $credentials->method('toHeaders')->willReturn($headers);

        return new UserApi($requestSender, $userTransformer, $credentials);
    }
}
