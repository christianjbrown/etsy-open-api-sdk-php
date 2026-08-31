<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ListingTranslationRequestInterface;

interface ListingTranslationRequestSerializerInterface
{
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_TAGS = 'tags';
    public const string KEY_TITLE = 'title';

    /**
     * @return array<string, string>
     */
    public function serialize(ListingTranslationRequestInterface $listingTranslationRequest): array;
}
