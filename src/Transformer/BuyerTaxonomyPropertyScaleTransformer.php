<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScale;
use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;

use function is_int;
use function is_string;

final class BuyerTaxonomyPropertyScaleTransformer implements BuyerTaxonomyPropertyScaleTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyPropertyScaleInterface
    {
        $scale = new BuyerTaxonomyPropertyScale();

        self::applyDescription($scale, $data);
        self::applyDisplayName($scale, $data);
        self::applyScaleId($scale, $data);

        return $scale;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(BuyerTaxonomyPropertyScale $scale, array $data): void
    {
        if (empty($data[self::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESCRIPTION])) {
            return;
        }
        $scale->setDescription($data[self::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDisplayName(BuyerTaxonomyPropertyScale $scale, array $data): void
    {
        if (empty($data[self::KEY_DISPLAY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_DISPLAY_NAME])) {
            return;
        }
        $scale->setDisplayName($data[self::KEY_DISPLAY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyScaleId(BuyerTaxonomyPropertyScale $scale, array $data): void
    {
        if (!isset($data[self::KEY_SCALE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SCALE_ID])) {
            return;
        }
        $scale->setScaleId($data[self::KEY_SCALE_ID]);
    }
}
