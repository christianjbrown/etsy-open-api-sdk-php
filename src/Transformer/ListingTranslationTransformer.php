<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingTranslation;
use ChristianBrown\Etsy\Model\ListingTranslationInterface;

use function array_values;
use function count;
use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class ListingTranslationTransformer implements ListingTranslationTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingTranslationInterface
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        $translation = new ListingTranslation($data[self::KEY_LISTING_ID]);

        self::applyDescription($translation, $data);
        self::applyLanguage($translation, $data);
        self::applyTags($translation, $data);
        self::applyTitle($translation, $data);

        return $translation;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(ListingTranslation $translation, array $data): void
    {
        if (empty($data[self::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESCRIPTION])) {
            return;
        }
        $translation->setDescription($data[self::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguage(ListingTranslation $translation, array $data): void
    {
        if (empty($data[self::KEY_LANGUAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_LANGUAGE])) {
            return;
        }
        $translation->setLanguage($data[self::KEY_LANGUAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTags(ListingTranslation $translation, array $data): void
    {
        if (!isset($data[self::KEY_TAGS])) {
            return;
        }
        if (!is_array($data[self::KEY_TAGS])) {
            return;
        }
        $tags = [];
        $items = array_values($data[self::KEY_TAGS]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $tags[] = $items[$i];
        }
        $translation->setTags($tags);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(ListingTranslation $translation, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $translation->setTitle($data[self::KEY_TITLE]);
    }
}
