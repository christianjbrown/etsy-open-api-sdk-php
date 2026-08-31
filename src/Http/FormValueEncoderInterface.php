<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

interface FormValueEncoderInterface
{
    /**
     * PHP's own bracketed array notation, which is how Etsy's form-encoded write endpoints take a
     * repeated field: `tags[0]=one&tags[1]=two`.
     */
    public const string INDEXED_KEY_SPRINTF = '%s[%d]';
    public const string VALUE_FALSE = 'false';
    public const string VALUE_TRUE = 'true';

    /**
     * Etsy's form-encoded endpoints take the literal strings `true` and `false`, not `1` and `0`.
     */
    public function encodeBool(bool $value): string;

    public function encodeFloat(float $value): string;

    public function encodeInt(int $value): string;

    /**
     * @param string          $key   The form field name the list belongs to
     * @param array<int, int> $value
     *
     * @return array<string, string>
     */
    public function encodeIntList(string $key, array $value): array;

    /**
     * @param string             $key   The form field name the list belongs to
     * @param array<int, string> $value
     *
     * @return array<string, string>
     */
    public function encodeStringList(string $key, array $value): array;
}
