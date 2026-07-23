<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopSectionInterface
{
    public function getActiveListingCount(): ?int;

    public function getRank(): ?int;

    public function getShopSectionId(): int;

    public function getTitle(): ?string;

    public function getUserId(): ?int;

    public function setActiveListingCount(?int $value): self;

    public function setRank(?int $value): self;

    public function setShopSectionId(int $value): self;

    public function setTitle(?string $value): self;

    public function setUserId(?int $value): self;
}
