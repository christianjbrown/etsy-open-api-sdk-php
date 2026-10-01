<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_int;

final class ListingWithAssociationsTimestampsFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyCreatedTimestamp($listing, $data);
        self::applyCreationTimestamp($listing, $data);
        self::applyEndingTimestamp($listing, $data);
        self::applyLastModifiedTimestamp($listing, $data);
        self::applyOriginalCreationTimestamp($listing, $data);
        self::applyStateTimestamp($listing, $data);
        self::applyUpdatedTimestamp($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $listing->setCreatedTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreationTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_CREATION_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_CREATION_TIMESTAMP])) {
            return;
        }
        $listing->setCreationTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_CREATION_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEndingTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ENDING_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_ENDING_TIMESTAMP])) {
            return;
        }
        $listing->setEndingTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_ENDING_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLastModifiedTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP])) {
            return;
        }
        $listing->setLastModifiedTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_LAST_MODIFIED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyOriginalCreationTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP])) {
            return;
        }
        $listing->setOriginalCreationTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_ORIGINAL_CREATION_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStateTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_STATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_STATE_TIMESTAMP])) {
            return;
        }
        $listing->setStateTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_STATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $listing->setUpdatedTimestamp($data[ListingWithAssociationsTransformerInterface::KEY_UPDATED_TIMESTAMP]);
    }
}
