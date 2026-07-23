<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\UserAddressInterface;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformer;
use ChristianBrown\Etsy\Transformer\UserAddressesTransformerInterface;
use ChristianBrown\Etsy\Transformer\UserAddressTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(UserAddressesTransformer::class)]
final class UserAddressesTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-address-1'], ['test-address-2']];

        $address1 = self::createStub(UserAddressInterface::class);
        $address2 = self::createStub(UserAddressInterface::class);

        $addressTransformer = self::createStub(UserAddressTransformerInterface::class);
        $addressTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-address-1'], $address1],
                    [['test-address-2'], $address2],
                ]
            );

        $transformer = new UserAddressesTransformer($addressTransformer);

        self::assertSame([$address1, $address2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $addressTransformer = self::createStub(UserAddressTransformerInterface::class);

        $transformer = new UserAddressesTransformer($addressTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $addressTransformer = self::createStub(UserAddressTransformerInterface::class);

        $transformer = new UserAddressesTransformer($addressTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(UserAddressesTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, UserAddressesTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
