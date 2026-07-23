<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformer;
use ChristianBrown\Etsy\Transformer\ShopReturnPoliciesTransformerInterface;
use ChristianBrown\Etsy\Transformer\ShopReturnPolicyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(ShopReturnPoliciesTransformer::class)]
final class ShopReturnPoliciesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['policy-1'], ['policy-2']];

        $policy1 = self::createStub(ShopReturnPolicyInterface::class);
        $policy2 = self::createStub(ShopReturnPolicyInterface::class);

        $shopReturnPolicyTransformer = self::createStub(ShopReturnPolicyTransformerInterface::class);
        $shopReturnPolicyTransformer->method('transform')
            ->willReturnMap(
                [
                    [['policy-1'], $policy1],
                    [['policy-2'], $policy2],
                ]
            );

        $transformer = new ShopReturnPoliciesTransformer($shopReturnPolicyTransformer);

        self::assertSame([$policy1, $policy2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $shopReturnPolicyTransformer = self::createStub(ShopReturnPolicyTransformerInterface::class);

        $transformer = new ShopReturnPoliciesTransformer($shopReturnPolicyTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $shopReturnPolicyTransformer = self::createStub(ShopReturnPolicyTransformerInterface::class);

        $transformer = new ShopReturnPoliciesTransformer($shopReturnPolicyTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopReturnPoliciesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, ShopReturnPoliciesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
