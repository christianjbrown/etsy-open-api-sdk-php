<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createListingTranslation` or `updateListingTranslation` call. Etsy takes the
 * same fields for both.
 */
interface ListingTranslationRequestInterface
{
    public function getDescription(): string;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    public function getTitle(): string;

    public function setDescription(string $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): self;

    public function setTitle(string $value): self;
}
