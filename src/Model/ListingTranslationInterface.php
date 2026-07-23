<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingTranslationInterface
{
    public function getDescription(): ?string;

    public function getLanguage(): ?string;

    public function getListingId(): int;

    /**
     * @return array<int, string>
     */
    public function getTags(): array;

    public function getTitle(): ?string;

    public function setDescription(?string $value): self;

    public function setLanguage(?string $value): self;

    public function setListingId(int $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): self;

    public function setTitle(?string $value): self;
}
