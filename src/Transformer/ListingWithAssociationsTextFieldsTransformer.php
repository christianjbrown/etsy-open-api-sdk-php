<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_string;

final class ListingWithAssociationsTextFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyDescription($listing, $data);
        self::applyRichDescription($listing, $data);
        self::applyTitle($listing, $data);
        self::applySuggestedTitle($listing, $data);
        self::applyLanguage($listing, $data);
        self::applyListingType($listing, $data);
        self::applyState($listing, $data);
        self::applyUrl($listing, $data);
        self::applyWhenMade($listing, $data);
        self::applyWhoMade($listing, $data);
        self::applyFileData($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_DESCRIPTION])) {
            return;
        }
        $listing->setDescription($data[ListingWithAssociationsTransformerInterface::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFileData(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_FILE_DATA])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_FILE_DATA])) {
            return;
        }
        $listing->setFileData($data[ListingWithAssociationsTransformerInterface::KEY_FILE_DATA]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguage(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_LANGUAGE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_LANGUAGE])) {
            return;
        }
        $listing->setLanguage($data[ListingWithAssociationsTransformerInterface::KEY_LANGUAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingType(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_LISTING_TYPE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_LISTING_TYPE])) {
            return;
        }
        $listing->setListingType($data[ListingWithAssociationsTransformerInterface::KEY_LISTING_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRichDescription(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_RICH_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_RICH_DESCRIPTION])) {
            return;
        }
        $listing->setRichDescription($data[ListingWithAssociationsTransformerInterface::KEY_RICH_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyState(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_STATE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_STATE])) {
            return;
        }
        $listing->setState($data[ListingWithAssociationsTransformerInterface::KEY_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySuggestedTitle(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_SUGGESTED_TITLE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_SUGGESTED_TITLE])) {
            return;
        }
        $listing->setSuggestedTitle($data[ListingWithAssociationsTransformerInterface::KEY_SUGGESTED_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_TITLE])) {
            return;
        }
        $listing->setTitle($data[ListingWithAssociationsTransformerInterface::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_URL])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_URL])) {
            return;
        }
        $listing->setUrl($data[ListingWithAssociationsTransformerInterface::KEY_URL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWhenMade(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_WHEN_MADE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_WHEN_MADE])) {
            return;
        }
        $listing->setWhenMade($data[ListingWithAssociationsTransformerInterface::KEY_WHEN_MADE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyWhoMade(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_WHO_MADE])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_WHO_MADE])) {
            return;
        }
        $listing->setWhoMade($data[ListingWithAssociationsTransformerInterface::KEY_WHO_MADE]);
    }
}
