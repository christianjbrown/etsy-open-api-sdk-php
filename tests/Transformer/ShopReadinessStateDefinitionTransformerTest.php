<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinition;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReadinessStateDefinition::class)]
#[CoversClass(ShopReadinessStateDefinitionTransformer::class)]
final class ShopReadinessStateDefinitionTransformerTest extends TestCase
{
    public function testSetReadinessStateId(): void
    {
        $definition = new ShopReadinessStateDefinition(1);

        self::assertSame(2, $definition->setReadinessStateId(2)->getReadinessStateId());
    }

    public function testTransform(): void
    {
        $data = [
            ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE_ID => 9000,
            ShopReadinessStateDefinitionTransformerInterface::KEY_MAX_PROCESSING_DAYS => 5,
            ShopReadinessStateDefinitionTransformerInterface::KEY_MIN_PROCESSING_DAYS => 3,
            ShopReadinessStateDefinitionTransformerInterface::KEY_PROCESSING_DAYS_DISPLAY_LABEL => '3 - 5 days',
            ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE => 'ready_to_ship',
            ShopReadinessStateDefinitionTransformerInterface::KEY_SHOP_ID => 100,
        ];

        $transformer = new ShopReadinessStateDefinitionTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getReadinessStateId());
        self::assertSame(5, $actual->getMaxProcessingDays());
        self::assertSame(3, $actual->getMinProcessingDays());
        self::assertSame('3 - 5 days', $actual->getProcessingDaysDisplayLabel());
        self::assertSame('ready_to_ship', $actual->getReadinessState());
        self::assertSame(100, $actual->getShopId());
    }

    /**
     * @param array<string, mixed>                                 $data
     * @param Closure(ShopReadinessStateDefinitionInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopReadinessStateDefinitionTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopReadinessStateDefinitionInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopReadinessStateDefinitionInterface $model): void {
                self::assertNull($model->getMaxProcessingDays());
                self::assertNull($model->getMinProcessingDays());
                self::assertNull($model->getProcessingDaysDisplayLabel());
                self::assertNull($model->getReadinessState());
                self::assertNull($model->getShopId());
            },
        ];

        yield 'maxProcessingDaysWrongType' => [[$id => 1, ShopReadinessStateDefinitionTransformerInterface::KEY_MAX_PROCESSING_DAYS => 'x'], static function (ShopReadinessStateDefinitionInterface $m): void {
            self::assertNull($m->getMaxProcessingDays());
        }];
        yield 'minProcessingDaysWrongType' => [[$id => 1, ShopReadinessStateDefinitionTransformerInterface::KEY_MIN_PROCESSING_DAYS => 'x'], static function (ShopReadinessStateDefinitionInterface $m): void {
            self::assertNull($m->getMinProcessingDays());
        }];
        yield 'processingDaysDisplayLabelWrongType' => [[$id => 1, ShopReadinessStateDefinitionTransformerInterface::KEY_PROCESSING_DAYS_DISPLAY_LABEL => 42], static function (ShopReadinessStateDefinitionInterface $m): void {
            self::assertNull($m->getProcessingDaysDisplayLabel());
        }];
        yield 'readinessStateWrongType' => [[$id => 1, ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE => 42], static function (ShopReadinessStateDefinitionInterface $m): void {
            self::assertNull($m->getReadinessState());
        }];
        yield 'shopIdWrongType' => [[$id => 1, ShopReadinessStateDefinitionTransformerInterface::KEY_SHOP_ID => 'x'], static function (ShopReadinessStateDefinitionInterface $m): void {
            self::assertNull($m->getShopId());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidReadinessStateId(array $data): void
    {
        $transformer = new ShopReadinessStateDefinitionTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopReadinessStateDefinitionTransformerInterface::KEY_READINESS_STATE_ID));

        $transformer->transform($data);
    }
}
