<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TransactionVariation;
use ChristianBrown\Etsy\Model\TransactionVariationInterface;

use function is_int;
use function is_string;

final class TransactionVariationTransformer implements TransactionVariationTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TransactionVariationInterface
    {
        $variation = new TransactionVariation();

        self::applyFormattedName($variation, $data);
        self::applyFormattedValue($variation, $data);
        self::applyPropertyId($variation, $data);
        self::applyQuestionId($variation, $data);
        self::applyValueId($variation, $data);

        return $variation;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFormattedName(TransactionVariation $variation, array $data): void
    {
        if (empty($data[self::KEY_FORMATTED_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_FORMATTED_NAME])) {
            return;
        }
        $variation->setFormattedName($data[self::KEY_FORMATTED_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFormattedValue(TransactionVariation $variation, array $data): void
    {
        if (empty($data[self::KEY_FORMATTED_VALUE])) {
            return;
        }
        if (!is_string($data[self::KEY_FORMATTED_VALUE])) {
            return;
        }
        $variation->setFormattedValue($data[self::KEY_FORMATTED_VALUE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPropertyId(TransactionVariation $variation, array $data): void
    {
        if (!isset($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PROPERTY_ID])) {
            return;
        }
        $variation->setPropertyId($data[self::KEY_PROPERTY_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuestionId(TransactionVariation $variation, array $data): void
    {
        if (!isset($data[self::KEY_QUESTION_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_QUESTION_ID])) {
            return;
        }
        $variation->setQuestionId($data[self::KEY_QUESTION_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyValueId(TransactionVariation $variation, array $data): void
    {
        if (!isset($data[self::KEY_VALUE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_VALUE_ID])) {
            return;
        }
        $variation->setValueId($data[self::KEY_VALUE_ID]);
    }
}
