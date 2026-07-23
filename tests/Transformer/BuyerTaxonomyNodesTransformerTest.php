<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodesTransformerInterface;
use ChristianBrown\Etsy\Transformer\BuyerTaxonomyNodeTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(BuyerTaxonomyNodesTransformer::class)]
final class BuyerTaxonomyNodesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['node-1'], ['node-2']];

        $node1 = self::createStub(BuyerTaxonomyNodeInterface::class);
        $node2 = self::createStub(BuyerTaxonomyNodeInterface::class);

        $nodeTransformer = self::createStub(BuyerTaxonomyNodeTransformerInterface::class);
        $nodeTransformer->method('transform')
            ->willReturnMap(
                [
                    [['node-1'], $node1],
                    [['node-2'], $node2],
                ]
            );

        $transformer = new BuyerTaxonomyNodesTransformer($nodeTransformer);

        self::assertSame([$node1, $node2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $nodeTransformer = self::createStub(BuyerTaxonomyNodeTransformerInterface::class);

        $transformer = new BuyerTaxonomyNodesTransformer($nodeTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $nodeTransformer = self::createStub(BuyerTaxonomyNodeTransformerInterface::class);

        $transformer = new BuyerTaxonomyNodesTransformer($nodeTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(BuyerTaxonomyNodesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, BuyerTaxonomyNodesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
