<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateVariationImagesRequestInterface;

interface UpdateVariationImagesRequestSerializerInterface
{
    public const string KEY_VARIATION_IMAGES = 'variation_images';

    /**
     * @return array<string, mixed>
     */
    public function serialize(UpdateVariationImagesRequestInterface $updateVariationImagesRequest): array;
}
