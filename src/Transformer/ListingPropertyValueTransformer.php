<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingPropertyValue;
use ChristianBrown\Etsy\Model\ListingPropertyValueInterface;

use function array_values;
use function count;
use function is_array;
use function is_int;
use function is_string;

final class ListingPropertyValueTransformer implements ListingPropertyValueTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingPropertyValueInterface
    {
        $propertyValue = new ListingPropertyValue();

        self::applyPropertyId($propertyValue, $data);
        self::applyPropertyName($propertyValue, $data);
        self::applyScaleId($propertyValue, $data);
        self::applyScaleName($propertyValue, $data);
        self::applyValueIds($propertyValue, $data);
        self::applyValues($propertyValue, $data);

        return $propertyValue;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPropertyId(ListingPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        $propertyValue->setPropertyId($data[self::KEY_PROPERTY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPropertyName(ListingPropertyValue $propertyValue, array $data): void
    {
        if (empty($data[self::KEY_PROPERTY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_PROPERTY_NAME])) {
            return;
        }
        $propertyValue->setPropertyName($data[self::KEY_PROPERTY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyScaleId(ListingPropertyValue $propertyValue, array $data): void
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
    private static function applyScaleName(ListingPropertyValue $propertyValue, array $data): void
    {
        if (empty($data[self::KEY_SCALE_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_SCALE_NAME])) {
            return;
        }
        $propertyValue->setScaleName($data[self::KEY_SCALE_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValueIds(ListingPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_VALUE_IDS])) {
            return;
        }
        if (!is_array($data[self::KEY_VALUE_IDS])) {
            return;
        }
        $valueIds = [];
        $values = array_values($data[self::KEY_VALUE_IDS]);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $valueId = $values[$i];
            if (!is_int($valueId)) {
                continue;
            }
            $valueIds[] = $valueId;
        }
        $propertyValue->setValueIds($valueIds);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValues(ListingPropertyValue $propertyValue, array $data): void
    {
        if (!isset($data[self::KEY_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_VALUES])) {
            return;
        }
        $values = [];
        $rawValues = array_values($data[self::KEY_VALUES]);
        for ($i = 0, $count = count($rawValues); $i < $count; ++$i) {
            $value = $rawValues[$i];
            if (!is_string($value)) {
                continue;
            }
            $values[] = $value;
        }
        $propertyValue->setValues($values);
    }
}
