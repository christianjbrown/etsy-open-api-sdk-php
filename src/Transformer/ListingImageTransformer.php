<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingImage;
use ChristianBrown\Etsy\Model\ListingImageInterface;

use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class ListingImageTransformer implements ListingImageTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingImageInterface
    {
        if (!isset($data[self::KEY_LISTING_IMAGE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_IMAGE_ID));
        }
        if (!is_int($data[self::KEY_LISTING_IMAGE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_IMAGE_ID));
        }
        $listingImage = new ListingImage($data[self::KEY_LISTING_IMAGE_ID]);

        self::applyAltText($listingImage, $data);
        self::applyBlue($listingImage, $data);
        self::applyBrightness($listingImage, $data);
        self::applyCreatedTimestamp($listingImage, $data);
        self::applyCreationTsz($listingImage, $data);
        self::applyFullHeight($listingImage, $data);
        self::applyFullWidth($listingImage, $data);
        self::applyGreen($listingImage, $data);
        self::applyHexCode($listingImage, $data);
        self::applyHue($listingImage, $data);
        self::applyIsBlackAndWhite($listingImage, $data);
        self::applyListingId($listingImage, $data);
        self::applyRank($listingImage, $data);
        self::applyRed($listingImage, $data);
        self::applySaturation($listingImage, $data);
        self::applyUrl170x135($listingImage, $data);
        self::applyUrl570xN($listingImage, $data);
        self::applyUrl75x75($listingImage, $data);
        self::applyUrlFullxfull($listingImage, $data);

        return $listingImage;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAltText(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_ALT_TEXT])) {
            return;
        }
        if (!is_string($data[self::KEY_ALT_TEXT])) {
            return;
        }
        $listingImage->setAltText($data[self::KEY_ALT_TEXT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBlue(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_BLUE])) {
            return;
        }
        if (!is_int($data[self::KEY_BLUE])) {
            return;
        }
        $listingImage->setBlue($data[self::KEY_BLUE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBrightness(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_BRIGHTNESS])) {
            return;
        }
        if (!is_int($data[self::KEY_BRIGHTNESS])) {
            return;
        }
        $listingImage->setBrightness($data[self::KEY_BRIGHTNESS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $listingImage->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreationTsz(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_CREATION_TSZ])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATION_TSZ])) {
            return;
        }
        $listingImage->setCreationTsz($data[self::KEY_CREATION_TSZ]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFullHeight(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_FULL_HEIGHT])) {
            return;
        }
        if (!is_int($data[self::KEY_FULL_HEIGHT])) {
            return;
        }
        $listingImage->setFullHeight($data[self::KEY_FULL_HEIGHT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFullWidth(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_FULL_WIDTH])) {
            return;
        }
        if (!is_int($data[self::KEY_FULL_WIDTH])) {
            return;
        }
        $listingImage->setFullWidth($data[self::KEY_FULL_WIDTH]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyGreen(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_GREEN])) {
            return;
        }
        if (!is_int($data[self::KEY_GREEN])) {
            return;
        }
        $listingImage->setGreen($data[self::KEY_GREEN]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHexCode(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_HEX_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_HEX_CODE])) {
            return;
        }
        $listingImage->setHexCode($data[self::KEY_HEX_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHue(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_HUE])) {
            return;
        }
        if (!is_int($data[self::KEY_HUE])) {
            return;
        }
        $listingImage->setHue($data[self::KEY_HUE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsBlackAndWhite(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_IS_BLACK_AND_WHITE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_BLACK_AND_WHITE])) {
            return;
        }
        $listingImage->setIsBlackAndWhite($data[self::KEY_IS_BLACK_AND_WHITE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingId(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            return;
        }
        $listingImage->setListingId($data[self::KEY_LISTING_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRank(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_RANK])) {
            return;
        }
        if (!is_int($data[self::KEY_RANK])) {
            return;
        }
        $listingImage->setRank($data[self::KEY_RANK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyRed(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_RED])) {
            return;
        }
        if (!is_int($data[self::KEY_RED])) {
            return;
        }
        $listingImage->setRed($data[self::KEY_RED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySaturation(ListingImage $listingImage, array $data): void
    {
        if (!isset($data[self::KEY_SATURATION])) {
            return;
        }
        if (!is_int($data[self::KEY_SATURATION])) {
            return;
        }
        $listingImage->setSaturation($data[self::KEY_SATURATION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl170x135(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_URL_170X135])) {
            return;
        }
        if (!is_string($data[self::KEY_URL_170X135])) {
            return;
        }
        $listingImage->setUrl170x135($data[self::KEY_URL_170X135]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl570xN(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_URL_570XN])) {
            return;
        }
        if (!is_string($data[self::KEY_URL_570XN])) {
            return;
        }
        $listingImage->setUrl570xN($data[self::KEY_URL_570XN]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl75x75(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_URL_75X75])) {
            return;
        }
        if (!is_string($data[self::KEY_URL_75X75])) {
            return;
        }
        $listingImage->setUrl75x75($data[self::KEY_URL_75X75]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrlFullxfull(ListingImage $listingImage, array $data): void
    {
        if (empty($data[self::KEY_URL_FULLXFULL])) {
            return;
        }
        if (!is_string($data[self::KEY_URL_FULLXFULL])) {
            return;
        }
        $listingImage->setUrlFullxfull($data[self::KEY_URL_FULLXFULL]);
    }
}
