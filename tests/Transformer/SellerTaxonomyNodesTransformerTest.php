<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformer;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodesTransformerInterface;
use ChristianBrown\Etsy\Transformer\SellerTaxonomyNodeTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(SellerTaxonomyNodesTransformer::class)]
final class SellerTaxonomyNodesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['node-1'], ['node-2']];

        $node1 = self::createStub(SellerTaxonomyNodeInterface::class);
        $node2 = self::createStub(SellerTaxonomyNodeInterface::class);

        $nodeTransformer = self::createStub(SellerTaxonomyNodeTransformerInterface::class);
        $nodeTransformer->method('transform')
            ->willReturnMap(
                [
                    [['node-1'], $node1],
                    [['node-2'], $node2],
                ]
            );

        $transformer = new SellerTaxonomyNodesTransformer($nodeTransformer);

        self::assertSame([$node1, $node2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $nodeTransformer = self::createStub(SellerTaxonomyNodeTransformerInterface::class);

        $transformer = new SellerTaxonomyNodesTransformer($nodeTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $nodeTransformer = self::createStub(SellerTaxonomyNodeTransformerInterface::class);

        $transformer = new SellerTaxonomyNodesTransformer($nodeTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(SellerTaxonomyNodesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, SellerTaxonomyNodesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
