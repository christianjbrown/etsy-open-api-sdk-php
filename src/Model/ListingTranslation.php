<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingTranslation implements ListingTranslationInterface
{
    private ?string $description = null;
    private ?string $language = null;
    private int $listingId;

    /**
     * @var array<int, string>
     */
    private array $tags = [];
    private ?string $title = null;

    public function __construct(int $listingId)
    {
        $this->listingId = $listingId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getListingId(): int
    {
        return $this->listingId;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setDescription(?string $value): ListingTranslationInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setLanguage(?string $value): ListingTranslationInterface
    {
        $this->language = $value;

        return $this;
    }

    public function setListingId(int $value): ListingTranslationInterface
    {
        $this->listingId = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): ListingTranslationInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTitle(?string $value): ListingTranslationInterface
    {
        $this->title = $value;

        return $this;
    }
}
