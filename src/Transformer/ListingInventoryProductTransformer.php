<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProduct;
use ChristianBrown\Etsy\Model\ListingInventoryProductInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class ListingInventoryProductTransformer implements ListingInventoryProductTransformerInterface
{
    private ListingInventoryProductOfferingsTransformerInterface $listingInventoryProductOfferingsTransformer;
    private ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer;

    public function __construct(ListingInventoryProductOfferingsTransformerInterface $listingInventoryProductOfferingsTransformer, ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer)
    {
        $this->listingInventoryProductOfferingsTransformer = $listingInventoryProductOfferingsTransformer;
        $this->listingPropertyValuesTransformer = $listingPropertyValuesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryProductInterface
    {
        if (!isset($data[self::KEY_PRODUCT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PRODUCT_ID));
        }
        if (!is_int($data[self::KEY_PRODUCT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PRODUCT_ID));
        }
        $product = new ListingInventoryProduct($data[self::KEY_PRODUCT_ID]);

        self::applySku($product, $data);
        self::applyIsDeleted($product, $data);
        $this->applyOfferings($product, $data);
        $this->applyPropertyValues($product, $data);

        return $product;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDeleted(ListingInventoryProduct $product, array $data): void
    {
        if (!isset($data[self::KEY_IS_DELETED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DELETED])) {
            return;
        }
        $product->setIsDeleted($data[self::KEY_IS_DELETED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyOfferings(ListingInventoryProduct $product, array $data): void
    {
        if (empty($data[self::KEY_OFFERINGS])) {
            return;
        }
        if (!is_array($data[self::KEY_OFFERINGS])) {
            return;
        }
        $product->setOfferings($this->listingInventoryProductOfferingsTransformer->transform($data[self::KEY_OFFERINGS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPropertyValues(ListingInventoryProduct $product, array $data): void
    {
        if (empty($data[self::KEY_PROPERTY_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_PROPERTY_VALUES])) {
            return;
        }
        $product->setPropertyValues($this->listingPropertyValuesTransformer->transform($data[self::KEY_PROPERTY_VALUES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySku(ListingInventoryProduct $product, array $data): void
    {
        if (empty($data[self::KEY_SKU])) {
            return;
        }
        if (!is_string($data[self::KEY_SKU])) {
            return;
        }
        $product->setSku($data[self::KEY_SKU]);
    }
}
