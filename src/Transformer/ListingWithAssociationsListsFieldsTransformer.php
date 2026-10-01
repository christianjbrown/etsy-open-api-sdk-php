<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function array_values;
use function count;
use function is_array;
use function is_string;

final class ListingWithAssociationsListsFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyMaterials($listing, $data);
        self::applySkus($listing, $data);
        self::applyStyle($listing, $data);
        self::applyTags($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaterials(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_MATERIALS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_MATERIALS])) {
            return;
        }
        $values = [];
        $items = array_values($data[ListingWithAssociationsTransformerInterface::KEY_MATERIALS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setMaterials($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySkus(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_SKUS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_SKUS])) {
            return;
        }
        $values = [];
        $items = array_values($data[ListingWithAssociationsTransformerInterface::KEY_SKUS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setSkus($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStyle(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_STYLE])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_STYLE])) {
            return;
        }
        $values = [];
        $items = array_values($data[ListingWithAssociationsTransformerInterface::KEY_STYLE]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setStyle($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTags(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_TAGS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_TAGS])) {
            return;
        }
        $values = [];
        $items = array_values($data[ListingWithAssociationsTransformerInterface::KEY_TAGS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $listing->setTags($values);
    }
}
