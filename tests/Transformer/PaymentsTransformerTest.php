<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentInterface;
use ChristianBrown\Etsy\Transformer\PaymentsTransformer;
use ChristianBrown\Etsy\Transformer\PaymentsTransformerInterface;
use ChristianBrown\Etsy\Transformer\PaymentTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(PaymentsTransformer::class)]
final class PaymentsTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [['test-payment-1'], ['test-payment-2']];

        $payment1 = self::createStub(PaymentInterface::class);
        $payment2 = self::createStub(PaymentInterface::class);

        $paymentTransformer = self::createStub(PaymentTransformerInterface::class);
        $paymentTransformer->method('transform')
            ->willReturnMap(
                [
                    [['test-payment-1'], $payment1],
                    [['test-payment-2'], $payment2],
                ]
            );

        $transformer = new PaymentsTransformer($paymentTransformer);

        self::assertSame([$payment1, $payment2], $transformer->transform($data));
    }

    public function testTransformEmpty(): void
    {
        $paymentTransformer = self::createStub(PaymentTransformerInterface::class);

        $transformer = new PaymentsTransformer($paymentTransformer);

        self::assertSame([], $transformer->transform([]));
    }

    public function testTransformThrowsOnNonArrayElement(): void
    {
        $paymentTransformer = self::createStub(PaymentTransformerInterface::class);

        $transformer = new PaymentsTransformer($paymentTransformer);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(PaymentsTransformerInterface::UNEXPECTED_ARRAY_SPRINTF, PaymentsTransformerInterface::ARRAY_NAME));

        $transformer->transform(['not-an-array']);
    }
}
