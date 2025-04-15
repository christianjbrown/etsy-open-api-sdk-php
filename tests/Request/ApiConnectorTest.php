<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Request;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use ChristianBrown\Etsy\Request\ApiConnector;
use ChristianBrown\Etsy\Request\AuthenticationManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiConnector::class)]
final class ApiConnectorTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function test(): void
    {
        $authenticationManager = $this->createMock(AuthenticationManagerInterface::class);
        $authenticationManager->method('getAuthHeaders')
            ->willReturn(['test-auth-headers']);

        $apiRequestSender = $this->createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->method('get')
            ->with('test-url', ['test-key' => 'test-value'], ['test-auth-headers'])
            ->willReturn(['test-response']);

        $apiConnector = new ApiConnector($authenticationManager, $apiRequestSender);
        $actual = $apiConnector->get('test-url', ['test-key' => 'test-value']);

        self::assertSame(['test-response'], $actual);
    }
}
