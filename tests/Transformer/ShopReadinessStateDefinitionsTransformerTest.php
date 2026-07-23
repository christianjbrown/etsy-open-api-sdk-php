<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformer;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionsTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReadinessStateDefinitionTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReadinessStateDefinitionsTransformer::class)]
final class ShopReadinessStateDefinitionsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['definition-1'], ['definition-2']];

        $definition1 = self::createStub(ShopReadinessStateDefinitionInterface::class);
        $definition2 = self::createStub(ShopReadinessStateDefinitionInterface::class);

        $shopReadinessStateDefinitionTransformer = self::createStub(ShopReadinessStateDefinitionTransformerInterface::class);
        $shopReadinessStateDefinitionTransformer->method('transform')
            ->willReturnMap(
                [
                    [['definition-1'], $definition1],
                    [['definition-2'], $definition2],
                ]
            );

        $transformer = new ShopReadinessStateDefinitionsTransformer($shopReadinessStateDefinitionTransformer);

        self::assertSame([$definition1, $definition2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopReadinessStateDefinitionTransformer = self::createStub(ShopReadinessStateDefinitionTransformerInterface::class);

        $transformer = new ShopReadinessStateDefinitionsTransformer($shopReadinessStateDefinitionTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopReadinessStateDefinitionTransformer = self::createStub(ShopReadinessStateDefinitionTransformerInterface::class);

        $transformer = new ShopReadinessStateDefinitionsTransformer($shopReadinessStateDefinitionTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReadinessStateDefinitionsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopReadinessStateDefinitionsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
