<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateVariationImagesRequestInterface;

final class UpdateVariationImagesRequestSerializer implements UpdateVariationImagesRequestSerializerInterface
{
    private ListingVariationImageRequestsSerializerInterface $listingVariationImageRequestsSerializer;

    public function __construct(ListingVariationImageRequestsSerializerInterface $listingVariationImageRequestsSerializer)
    {
        $this->listingVariationImageRequestsSerializer = $listingVariationImageRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(UpdateVariationImagesRequestInterface $updateVariationImagesRequest): array
    {
        $data = [];

        $data = $this->applyVariationImages($data, $updateVariationImagesRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyVariationImages(array $data, UpdateVariationImagesRequestInterface $updateVariationImagesRequest): array
    {
        $data[self::KEY_VARIATION_IMAGES] = $this->listingVariationImageRequestsSerializer->serialize($updateVariationImagesRequest->getVariationImages());

        return $data;
    }
}
