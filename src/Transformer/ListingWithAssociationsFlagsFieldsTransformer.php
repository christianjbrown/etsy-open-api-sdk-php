<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_bool;

final class ListingWithAssociationsFlagsFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyHasVariations($listing, $data);
        self::applyIsCustomizable($listing, $data);
        self::applyIsPersonalizable($listing, $data);
        self::applyIsPrivate($listing, $data);
        self::applyIsSupply($listing, $data);
        self::applyIsTaxable($listing, $data);
        self::applyNonTaxable($listing, $data);
        self::applyShouldAutoRenew($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHasVariations(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_HAS_VARIATIONS])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_HAS_VARIATIONS])) {
            return;
        }
        $listing->setHasVariations($data[ListingWithAssociationsTransformerInterface::KEY_HAS_VARIATIONS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsCustomizable(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_IS_CUSTOMIZABLE])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_IS_CUSTOMIZABLE])) {
            return;
        }
        $listing->setIsCustomizable($data[ListingWithAssociationsTransformerInterface::KEY_IS_CUSTOMIZABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsPersonalizable(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_IS_PERSONALIZABLE])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_IS_PERSONALIZABLE])) {
            return;
        }
        $listing->setIsPersonalizable($data[ListingWithAssociationsTransformerInterface::KEY_IS_PERSONALIZABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsPrivate(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_IS_PRIVATE])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_IS_PRIVATE])) {
            return;
        }
        $listing->setIsPrivate($data[ListingWithAssociationsTransformerInterface::KEY_IS_PRIVATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsSupply(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_IS_SUPPLY])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_IS_SUPPLY])) {
            return;
        }
        $listing->setIsSupply($data[ListingWithAssociationsTransformerInterface::KEY_IS_SUPPLY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsTaxable(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_IS_TAXABLE])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_IS_TAXABLE])) {
            return;
        }
        $listing->setIsTaxable($data[ListingWithAssociationsTransformerInterface::KEY_IS_TAXABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNonTaxable(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_NON_TAXABLE])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_NON_TAXABLE])) {
            return;
        }
        $listing->setNonTaxable($data[ListingWithAssociationsTransformerInterface::KEY_NON_TAXABLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShouldAutoRenew(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_SHOULD_AUTO_RENEW])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_SHOULD_AUTO_RENEW])) {
            return;
        }
        $listing->setShouldAutoRenew($data[ListingWithAssociationsTransformerInterface::KEY_SHOULD_AUTO_RENEW]);
    }
}
