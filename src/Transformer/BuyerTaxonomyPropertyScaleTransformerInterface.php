<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\BuyerTaxonomyPropertyScaleInterface;

interface BuyerTaxonomyPropertyScaleTransformerInterface
{
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_DISPLAY_NAME = 'display_name';
    public const string KEY_SCALE_ID = 'scale_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyPropertyScaleInterface;
}
