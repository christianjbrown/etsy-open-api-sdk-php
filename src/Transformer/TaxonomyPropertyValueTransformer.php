<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyPropertyValue;
use ChristianBrown\Etsy\Model\TaxonomyPropertyValueInterface;

use function array_values;
use function count;
use function is_array;
use function is_int;
use function is_string;

final class TaxonomyPropertyValueTransformer implements TaxonomyPropertyValueTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TaxonomyPropertyValueInterface
    {
        $propertyValue = new TaxonomyPropertyValue();

        self::applyEqualTo($propertyValue, $data);
        self::applyName($propertyValue, $data);
        self::applyScaleId($propertyValue, $data);
        self::applyValueId($propertyValue, $data);

        return $propertyValue;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEqualTo(TaxonomyPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_EQUAL_TO])) {
            return;
        }
        if (!is_array($data[self::KEY_EQUAL_TO])) {
            return;
        }
        $equalTo = [];
        $values = array_values($data[self::KEY_EQUAL_TO]);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $valueId = $values[$i];
            if (!is_int($valueId)) {
                continue;
            }
            $equalTo[] = $valueId;
        }
        $propertyValue->setEqualTo($equalTo);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(TaxonomyPropertyValue $propertyValue, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $propertyValue->setName($data[self::KEY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyScaleId(TaxonomyPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_SCALE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SCALE_ID])) {
            return;
        }
        $propertyValue->setScaleId($data[self::KEY_SCALE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValueId(TaxonomyPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_VALUE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_VALUE_ID])) {
            return;
        }
        $propertyValue->setValueId($data[self::KEY_VALUE_ID]);
    }
}
