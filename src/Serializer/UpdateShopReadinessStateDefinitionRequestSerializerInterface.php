<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequestInterface;

interface UpdateShopReadinessStateDefinitionRequestSerializerInterface
{
    public const string KEY_MAX_PROCESSING_TIME = 'max_processing_time';
    public const string KEY_MIN_PROCESSING_TIME = 'min_processing_time';
    public const string KEY_PROCESSING_TIME_UNIT = 'processing_time_unit';
    public const string KEY_READINESS_STATE = 'readiness_state';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array;
}
