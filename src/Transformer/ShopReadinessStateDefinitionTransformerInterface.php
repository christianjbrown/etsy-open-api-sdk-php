<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;

interface ShopReadinessStateDefinitionTransformerInterface
{
    public const string KEY_MAX_PROCESSING_DAYS = 'max_processing_days';
    public const string KEY_MIN_PROCESSING_DAYS = 'min_processing_days';
    public const string KEY_PROCESSING_DAYS_DISPLAY_LABEL = 'processing_days_display_label';
    public const string KEY_READINESS_STATE = 'readiness_state';
    public const string KEY_READINESS_STATE_ID = 'readiness_state_id';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopReadinessStateDefinitionInterface;
}
