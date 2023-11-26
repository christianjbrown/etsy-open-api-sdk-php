<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Request;

use ChristianBrown\Etsy\Request\ApiConnector;
use ChristianBrown\Etsy\Request\ApiConnectorInterface;
use ChristianBrown\Etsy\Request\AuthenticationManagerInterface;
use ChristianBrown\JsonApiClient\RequestSenderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiConnector::class)]
final class ApiConnectorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $authenticationManager = $this->createMock(AuthenticationManagerInterface::class);
        $authenticationManager->method('getAuthHeaders')
            ->willReturn(['test-auth-headers']);

        $requestSender = $this->createMock(RequestSenderInterface::class);
        $requestSender->method('get')
            ->with(ApiConnectorInterface::FRIENDLY_NAME, 'test-url', ['test-key' => 'test-value'], ['test-auth-headers'])
            ->willReturn(['test-response']);

        $apiConnector = new ApiConnector($authenticationManager, $requestSender);
        $actual = $apiConnector->get('test-url', ['test-key' => 'test-value']);

        self::assertSame(['test-response'], $actual);
    }
}
