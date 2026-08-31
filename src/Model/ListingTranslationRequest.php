<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingTranslationRequest implements ListingTranslationRequestInterface
{
    private string $description;

    /**
     * @var array<int, string>
     */
    private array $tags = [];
    private string $title;

    public function __construct(string $title, string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $value): ListingTranslationRequestInterface
    {
        $this->description = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): ListingTranslationRequestInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTitle(string $value): ListingTranslationRequestInterface
    {
        $this->title = $value;

        return $this;
    }
}
