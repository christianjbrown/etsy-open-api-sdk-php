<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Http;

use function array_values;
use function count;
use function sprintf;

final class FormValueEncoder implements FormValueEncoderInterface
{
    public function encodeBool(bool $value): string
    {
        if ($value) {
            return self::VALUE_TRUE;
        }

        return self::VALUE_FALSE;
    }

    public function encodeFloat(float $value): string
    {
        return (string) $value;
    }

    public function encodeInt(int $value): string
    {
        return (string) $value;
    }

    /**
     * @param string          $key   The form field name the list belongs to
     * @param array<int, int> $value
     *
     * @return array<string, string>
     */
    public function encodeIntList(string $key, array $value): array
    {
        $data = [];
        $values = array_values($value);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[sprintf(self::INDEXED_KEY_SPRINTF, $key, $i)] = (string) $values[$i];
        }

        return $data;
    }

    /**
     * @param string             $key   The form field name the list belongs to
     * @param array<int, string> $value
     *
     * @return array<string, string>
     */
    public function encodeStringList(string $key, array $value): array
    {
        $data = [];
        $values = array_values($value);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $data[sprintf(self::INDEXED_KEY_SPRINTF, $key, $i)] = $values[$i];
        }

        return $data;
    }
}
