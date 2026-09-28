<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Http;

use ChristianBrown\ApiClient\JsonApiRequestSenderInterface;
use ChristianBrown\Etsy\Host\EtsyHost;
use ChristianBrown\Etsy\Http\HostRewritingJsonApiRequestSender;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HostRewritingJsonApiRequestSender::class)]
#[UsesClass(EtsyHost::class)]
final class HostRewritingJsonApiRequestSenderTest extends TestCase
{
    private const string PRODUCTION_URL = 'https://openapi.etsy.com/v3/application/shops/42/receipts';
    private const string SANDBOX_URL = 'https://api.sandbox.etsy.com/v3/application/shops/42/receipts';

    public function testDeleteRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('delete')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->delete(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v']));
    }

    public function testGetRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('get')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->get(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v']));
    }

    public function testLeavesAnAlreadyProductionUrlUnchangedWhenHostIsDefault(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('get')->with(self::PRODUCTION_URL)->willReturn(['ok']);

        $sender = new HostRewritingJsonApiRequestSender($inner, new EtsyHost());

        self::assertSame(['ok'], $sender->get(self::PRODUCTION_URL));
    }

    public function testPatchFormRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('patchForm')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->patchForm(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v']));
    }

    public function testPatchRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('patch')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['body'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->patch(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['body']));
    }

    public function testPostFormRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('postForm')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->postForm(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v']));
    }

    public function testPostRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('post')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['body'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->post(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['body']));
    }

    public function testPutFormRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('putForm')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->putForm(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['f' => 'v']));
    }

    public function testPutRewritesTheHost(): void
    {
        $inner = self::createMock(JsonApiRequestSenderInterface::class);
        $inner->expects(self::once())->method('put')->with(self::SANDBOX_URL, ['a' => 'b'], ['h' => 'v'], ['body'])->willReturn(['ok']);

        $sender = $this->buildSender($inner);

        self::assertSame(['ok'], $sender->put(self::PRODUCTION_URL, ['a' => 'b'], ['h' => 'v'], ['body']));
    }

    private function buildSender(JsonApiRequestSenderInterface $inner): HostRewritingJsonApiRequestSender
    {
        return new HostRewritingJsonApiRequestSender($inner, new EtsyHost('https://api.sandbox.etsy.com'));
    }
}
