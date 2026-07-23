<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopSection implements ShopSectionInterface
{
    private ?int $activeListingCount = null;
    private ?int $rank = null;
    private int $shopSectionId;
    private ?string $title = null;
    private ?int $userId = null;

    public function __construct(int $shopSectionId)
    {
        $this->shopSectionId = $shopSectionId;
    }

    public function getActiveListingCount(): ?int
    {
        return $this->activeListingCount;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function getShopSectionId(): int
    {
        return $this->shopSectionId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setActiveListingCount(?int $value): ShopSectionInterface
    {
        $this->activeListingCount = $value;

        return $this;
    }

    public function setRank(?int $value): ShopSectionInterface
    {
        $this->rank = $value;

        return $this;
    }

    public function setShopSectionId(int $value): ShopSectionInterface
    {
        $this->shopSectionId = $value;

        return $this;
    }

    public function setTitle(?string $value): ShopSectionInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setUserId(?int $value): ShopSectionInterface
    {
        $this->userId = $value;

        return $this;
    }
}
