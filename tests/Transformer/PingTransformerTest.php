<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Ping;
use ChristianBrown\Etsy\Transformer\PingTransformer;
use ChristianBrown\Etsy\Transformer\PingTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Ping::class)]
#[CoversClass(PingTransformer::class)]
final class PingTransformerTest extends TestCase
{
    public function testSetApplicationId(): void
    {
        $ping = new Ping(1);

        self::assertSame(2, $ping->setApplicationId(2)->getApplicationId());
    }

    public function testTransform(): void
    {
        $transformer = new PingTransformer();

        $actual = $transformer->transform([PingTransformerInterface::KEY_APPLICATION_ID => 9000]);

        self::assertSame(9000, $actual->getApplicationId());
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[PingTransformerInterface::KEY_APPLICATION_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidApplicationId(array $data): void
    {
        $transformer = new PingTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PingTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, PingTransformerInterface::KEY_APPLICATION_ID));

        $transformer->transform($data);
    }
}
