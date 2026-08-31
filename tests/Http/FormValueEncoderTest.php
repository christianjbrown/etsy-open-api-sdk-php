<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Http;

use ChristianBrown\Etsy\Http\FormValueEncoder;
use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormValueEncoder::class)]
final class FormValueEncoderTest extends TestCase
{
    #[TestWith([true, FormValueEncoderInterface::VALUE_TRUE])]
    #[TestWith([false, FormValueEncoderInterface::VALUE_FALSE])]
    public function testEncodeBool(bool $value, string $expected): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame($expected, $encoder->encodeBool($value));
    }

    public function testEncodeFloat(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame('12.5', $encoder->encodeFloat(12.5));
    }

    public function testEncodeInt(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame('42', $encoder->encodeInt(42));
    }

    public function testEncodeIntList(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame(['image_ids[0]' => '1', 'image_ids[1]' => '2'], $encoder->encodeIntList('image_ids', [1, 2]));
    }

    public function testEncodeIntListEmpty(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame([], $encoder->encodeIntList('image_ids', []));
    }

    public function testEncodeStringList(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame(['tags[0]' => 'one', 'tags[1]' => 'two'], $encoder->encodeStringList('tags', ['one', 'two']));
    }

    public function testEncodeStringListEmpty(): void
    {
        $encoder = new FormValueEncoder();

        self::assertSame([], $encoder->encodeStringList('tags', []));
    }
}
