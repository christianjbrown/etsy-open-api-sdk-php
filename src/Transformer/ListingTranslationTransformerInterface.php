<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingTranslationInterface;

interface ListingTranslationTransformerInterface
{
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_LANGUAGE = 'language';
    public const string KEY_LISTING_ID = 'listing_id';
    public const string KEY_TAGS = 'tags';
    public const string KEY_TITLE = 'title';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingTranslationInterface;
}
