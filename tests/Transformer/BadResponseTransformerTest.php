<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Transformer\BadResponseTransformer;
use ChristianBrown\Etsy\Transformer\BadResponseTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

use function sprintf;

#[CoversClass(BadResponseTransformer::class)]
final class BadResponseTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetFriendlyErrorFromBadResponse(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(42);

        $transformer = new BadResponseTransformer();
        $actual = $transformer->getFriendlyErrorFromBadResponse($response);
        $expected = sprintf(BadResponseTransformerInterface::MESSAGE_GENERIC, 42, BadResponseTransformerInterface::FRIENDLY_NAME);

        self::assertSame($expected, $actual);
    }

    /**
     * @throws Exception
     */
    public function testGetFriendlyErrorFromBadResponseJsonData(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(42);

        $transformer = new BadResponseTransformer();
        $data = [
            BadResponseTransformerInterface::ERROR_DESCRIPTION_KEY => 'test-error-description',
        ];
        $actual = $transformer->getFriendlyErrorFromBadResponseJsonData($response, $data);
        $expected = sprintf(BadResponseTransformerInterface::MESSAGE_FROM_ERROR_DESCRIPTION, 42, BadResponseTransformerInterface::FRIENDLY_NAME, 'test-error-description');

        self::assertSame($expected, $actual);
    }

    /**
     * @throws Exception
     */
    #[TestWith(
        [
            [],
        ],
    )]
    #[TestWith(
        [
            [
                BadResponseTransformerInterface::ERROR_DESCRIPTION_KEY => 42,
            ],
        ],
    )]
    #[TestWith(
        [
            [
                BadResponseTransformerInterface::ERROR_DESCRIPTION_KEY => '',
            ],
        ],
    )]
    public function testGetFriendlyErrorFromBadResponseJsonDataMissing(array $data): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(42);

        $transformer = new BadResponseTransformer();
        $actual = $transformer->getFriendlyErrorFromBadResponseJsonData($response, $data);
        $expected = sprintf(BadResponseTransformerInterface::MESSAGE_GENERIC, 42, BadResponseTransformerInterface::FRIENDLY_NAME);

        self::assertSame($expected, $actual);
    }
}
