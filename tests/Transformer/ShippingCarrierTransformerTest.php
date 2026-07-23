<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShippingCarrier;
use ChristianBrown\Etsy\Model\ShippingCarrierInterface;
use ChristianBrown\Etsy\Model\ShippingCarrierMailClassInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierMailClassesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShippingCarrierTransformer;
use ChristianBrown\Etsy\Transformer\ShippingCarrierTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShippingCarrier::class)]
#[CoversClass(ShippingCarrierTransformer::class)]
final class ShippingCarrierTransformerTest extends TestCase
{
    public function testSetShippingCarrierId(): void
    {
        $shippingCarrier = new ShippingCarrier(1);

        self::assertSame(2, $shippingCarrier->setShippingCarrierId(2)->getShippingCarrierId());
    }

    public function testTransform(): void
    {
        $domesticData = ['__domestic__'];
        $internationalData = ['__international__'];

        $data = [
            ShippingCarrierTransformerInterface::KEY_SHIPPING_CARRIER_ID => 9000,
            ShippingCarrierTransformerInterface::KEY_NAME => 'USPS',
            ShippingCarrierTransformerInterface::KEY_DOMESTIC_CLASSES => $domesticData,
            ShippingCarrierTransformerInterface::KEY_INTERNATIONAL_CLASSES => $internationalData,
        ];

        $domesticClass = self::createStub(ShippingCarrierMailClassInterface::class);
        $internationalClass = self::createStub(ShippingCarrierMailClassInterface::class);

        $mailClassesTransformer = self::createStub(ShippingCarrierMailClassesTransformerInterface::class);
        $mailClassesTransformer->method('transform')
            ->willReturnMap(
                [
                    [$domesticData, [$domesticClass]],
                    [$internationalData, [$internationalClass]],
                ]
            );

        $transformer = new ShippingCarrierTransformer($mailClassesTransformer);

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getShippingCarrierId());
        self::assertSame('USPS', $actual->getName());
        self::assertSame([$domesticClass], $actual->getDomesticClasses());
        self::assertSame([$internationalClass], $actual->getInternationalClasses());
    }

    /**
     * @param array<string, mixed>                    $data
     * @param Closure(ShippingCarrierInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShippingCarrierTransformer(self::createStub(ShippingCarrierMailClassesTransformerInterface::class));

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShippingCarrierInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShippingCarrierTransformerInterface::KEY_SHIPPING_CARRIER_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShippingCarrierInterface $model): void {
                self::assertNull($model->getName());
                self::assertSame([], $model->getDomesticClasses());
                self::assertSame([], $model->getInternationalClasses());
            },
        ];

        yield 'nameWrongType' => [[$id => 1, ShippingCarrierTransformerInterface::KEY_NAME => 42], static function (ShippingCarrierInterface $m): void {
            self::assertNull($m->getName());
        }];
        yield 'domesticClassesNonArray' => [[$id => 1, ShippingCarrierTransformerInterface::KEY_DOMESTIC_CLASSES => 'x'], static function (ShippingCarrierInterface $m): void {
            self::assertSame([], $m->getDomesticClasses());
        }];
        yield 'internationalClassesNonArray' => [[$id => 1, ShippingCarrierTransformerInterface::KEY_INTERNATIONAL_CLASSES => 'x'], static function (ShippingCarrierInterface $m): void {
            self::assertSame([], $m->getInternationalClasses());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShippingCarrierTransformerInterface::KEY_SHIPPING_CARRIER_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidShippingCarrierId(array $data): void
    {
        $transformer = new ShippingCarrierTransformer(self::createStub(ShippingCarrierMailClassesTransformerInterface::class));

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShippingCarrierTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShippingCarrierTransformerInterface::KEY_SHIPPING_CARRIER_ID));

        $transformer->transform($data);
    }
}
