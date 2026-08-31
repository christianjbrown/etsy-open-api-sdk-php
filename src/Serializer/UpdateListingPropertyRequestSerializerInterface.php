<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateListingPropertyRequestInterface;

interface UpdateListingPropertyRequestSerializerInterface
{
    public const string KEY_SCALE_ID = 'scale_id';
    public const string KEY_VALUE_IDS = 'value_ids';
    public const string KEY_VALUES = 'values';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateListingPropertyRequestInterface $updateListingPropertyRequest): array;
}
