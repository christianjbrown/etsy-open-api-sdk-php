<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\ShippingCarrierMailClass;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShippingCarrierMailClass::class)]
#[CoversClass(ShippingCarrierMailClassTransformer::class)]
final class ShippingCarrierMailClassTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            ShippingCarrierMailClassTransformerInterface::KEY_MAIL_CLASS_KEY => 'usps_priority',
            ShippingCarrierMailClassTransformerInterface::KEY_NAME => 'USPS Priority',
        ];

        $transformer = new ShippingCarrierMailClassTransformer();

        $actual = $transformer->transform($data);

        self::assertSame('usps_priority', $actual->getMailClassKey());
        self::assertSame('USPS Priority', $actual->getName());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?string $expectedMailClassKey, ?string $expectedName): void
    {
        $transformer = new ShippingCarrierMailClassTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedMailClassKey, $actual->getMailClassKey());
        self::assertSame($expectedName, $actual->getName());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?string, ?string}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $mailClassKey = ShippingCarrierMailClassTransformerInterface::KEY_MAIL_CLASS_KEY;
        $name = ShippingCarrierMailClassTransformerInterface::KEY_NAME;

        yield 'allAbsent' => [[], null, null];
        yield 'mailClassKeyValid' => [[$mailClassKey => 'fedex_ground'], 'fedex_ground', null];
        yield 'mailClassKeyWrongType' => [[$mailClassKey => 42], null, null];
        yield 'nameValid' => [[$name => 'FedEx Ground'], null, 'FedEx Ground'];
        yield 'nameWrongType' => [[$name => 42], null, null];
    }
}
