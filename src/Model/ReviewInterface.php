<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ReviewInterface
{
    public function getBuyerUserId(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getImageUrlFullxfull(): ?string;

    public function getLanguage(): ?string;

    public function getListingId(): ?int;

    public function getRating(): ?int;

    public function getReview(): ?string;

    public function getShopId(): ?int;

    public function getTransactionId(): ?int;

    public function getUpdatedTimestamp(): ?int;

    public function getUpdateTimestamp(): ?int;

    public function setBuyerUserId(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setImageUrlFullxfull(?string $value): self;

    public function setLanguage(?string $value): self;

    public function setListingId(?int $value): self;

    public function setRating(?int $value): self;

    public function setReview(?string $value): self;

    public function setShopId(?int $value): self;

    public function setTransactionId(?int $value): self;

    public function setUpdatedTimestamp(?int $value): self;

    public function setUpdateTimestamp(?int $value): self;
}
