<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingInventory;
use ChristianBrown\Etsy\Model\ListingInventoryInterface;

use function array_values;
use function count;
use function is_array;
use function is_int;

final class ListingInventoryTransformer implements ListingInventoryTransformerInterface
{
    private ListingInventoryProductsTransformerInterface $listingInventoryProductsTransformer;
    private ListingTransformerInterface $listingTransformer;

    public function __construct(ListingInventoryProductsTransformerInterface $listingInventoryProductsTransformer, ListingTransformerInterface $listingTransformer)
    {
        $this->listingInventoryProductsTransformer = $listingInventoryProductsTransformer;
        $this->listingTransformer = $listingTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryInterface
    {
        $inventory = new ListingInventory();

        $this->applyProducts($inventory, $data);
        self::applyPriceOnProperty($inventory, $data);
        self::applyQuantityOnProperty($inventory, $data);
        self::applySkuOnProperty($inventory, $data);
        self::applyReadinessStateOnProperty($inventory, $data);
        $this->applyListing($inventory, $data);

        return $inventory;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyListing(ListingInventory $inventory, array $data): void
    {
        if (empty($data[self::KEY_LISTING])) {
            return;
        }
        if (!is_array($data[self::KEY_LISTING])) {
            return;
        }
        $inventory->setListing($this->listingTransformer->transform($data[self::KEY_LISTING]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPriceOnProperty(ListingInventory $inventory, array $data): void
    {
        if (!isset($data[self::KEY_PRICE_ON_PROPERTY])) {
            return;
        }
        if (!is_array($data[self::KEY_PRICE_ON_PROPERTY])) {
            return;
        }
        $inventory->setPriceOnProperty(self::filterIntArray($data[self::KEY_PRICE_ON_PROPERTY]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyProducts(ListingInventory $inventory, array $data): void
    {
        if (empty($data[self::KEY_PRODUCTS])) {
            return;
        }
        if (!is_array($data[self::KEY_PRODUCTS])) {
            return;
        }
        $inventory->setProducts($this->listingInventoryProductsTransformer->transform($data[self::KEY_PRODUCTS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantityOnProperty(ListingInventory $inventory, array $data): void
    {
        if (!isset($data[self::KEY_QUANTITY_ON_PROPERTY])) {
            return;
        }
        if (!is_array($data[self::KEY_QUANTITY_ON_PROPERTY])) {
            return;
        }
        $inventory->setQuantityOnProperty(self::filterIntArray($data[self::KEY_QUANTITY_ON_PROPERTY]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReadinessStateOnProperty(ListingInventory $inventory, array $data): void
    {
        if (!isset($data[self::KEY_READINESS_STATE_ON_PROPERTY])) {
            return;
        }
        if (!is_array($data[self::KEY_READINESS_STATE_ON_PROPERTY])) {
            return;
        }
        $inventory->setReadinessStateOnProperty(self::filterIntArray($data[self::KEY_READINESS_STATE_ON_PROPERTY]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySkuOnProperty(ListingInventory $inventory, array $data): void
    {
        if (!isset($data[self::KEY_SKU_ON_PROPERTY])) {
            return;
        }
        if (!is_array($data[self::KEY_SKU_ON_PROPERTY])) {
            return;
        }
        $inventory->setSkuOnProperty(self::filterIntArray($data[self::KEY_SKU_ON_PROPERTY]));
    }

    /**
     * @phpstan-param mixed[] $data
     *
     * @return array<int, int>
     */
    private static function filterIntArray(array $data): array
    {
        $ids = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $value = $values[$i];
            if (!is_int($value)) {
                continue;
            }
            $ids[] = $value;
        }

        return $ids;
    }
}
