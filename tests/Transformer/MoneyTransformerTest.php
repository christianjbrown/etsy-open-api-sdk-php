<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Model\Money;
use ChristianBrown\Etsy\Transformer\MoneyTransformer;
use ChristianBrown\Etsy\Transformer\MoneyTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Money::class)]
#[CoversClass(MoneyTransformer::class)]
final class MoneyTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $data = [
            MoneyTransformerInterface::KEY_AMOUNT => 1234,
            MoneyTransformerInterface::KEY_CURRENCY_CODE => 'USD',
            MoneyTransformerInterface::KEY_DIVISOR => 100,
        ];

        $transformer = new MoneyTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(1234, $actual->getAmount());
        self::assertSame('USD', $actual->getCurrencyCode());
        self::assertSame(100, $actual->getDivisor());
    }

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, ?int $expectedAmount, ?string $expectedCurrencyCode, ?int $expectedDivisor): void
    {
        $transformer = new MoneyTransformer();

        $actual = $transformer->transform($data);

        self::assertSame($expectedAmount, $actual->getAmount());
        self::assertSame($expectedCurrencyCode, $actual->getCurrencyCode());
        self::assertSame($expectedDivisor, $actual->getDivisor());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, ?int, ?string, ?int}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $amount = MoneyTransformerInterface::KEY_AMOUNT;
        $currencyCode = MoneyTransformerInterface::KEY_CURRENCY_CODE;
        $divisor = MoneyTransformerInterface::KEY_DIVISOR;

        yield 'allAbsent' => [[], null, null, null];
        yield 'amountZero' => [[$amount => 0], 0, null, null];
        yield 'amountWrongType' => [[$amount => 'not-int'], null, null, null];
        yield 'currencyCodeValid' => [[$currencyCode => 'GBP'], null, 'GBP', null];
        yield 'currencyCodeWrongType' => [[$currencyCode => 42], null, null, null];
        yield 'divisorZero' => [[$divisor => 0], null, null, 0];
        yield 'divisorWrongType' => [[$divisor => 'not-int'], null, null, null];
    }
}
