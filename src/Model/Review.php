<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Review implements ReviewInterface
{
    private ?int $buyerUserId = null;
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?string $imageUrlFullxfull = null;
    private ?string $language = null;
    private ?int $listingId = null;
    private ?int $rating = null;
    private ?string $review = null;
    private ?int $shopId = null;
    private ?int $transactionId = null;
    private ?int $updatedTimestamp = null;
    private ?int $updateTimestamp = null;

    public function getBuyerUserId(): ?int
    {
        return $this->buyerUserId;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreateTimestamp(): ?int
    {
        return $this->createTimestamp;
    }

    public function getImageUrlFullxfull(): ?string
    {
        return $this->imageUrlFullxfull;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getTransactionId(): ?int
    {
        return $this->transactionId;
    }

    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    public function getUpdateTimestamp(): ?int
    {
        return $this->updateTimestamp;
    }

    public function setBuyerUserId(?int $value): ReviewInterface
    {
        $this->buyerUserId = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ReviewInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): ReviewInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setImageUrlFullxfull(?string $value): ReviewInterface
    {
        $this->imageUrlFullxfull = $value;

        return $this;
    }

    public function setLanguage(?string $value): ReviewInterface
    {
        $this->language = $value;

        return $this;
    }

    public function setListingId(?int $value): ReviewInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setRating(?int $value): ReviewInterface
    {
        $this->rating = $value;

        return $this;
    }

    public function setReview(?string $value): ReviewInterface
    {
        $this->review = $value;

        return $this;
    }

    public function setShopId(?int $value): ReviewInterface
    {
        $this->shopId = $value;

        return $this;
    }

    public function setTransactionId(?int $value): ReviewInterface
    {
        $this->transactionId = $value;

        return $this;
    }

    public function setUpdatedTimestamp(?int $value): ReviewInterface
    {
        $this->updatedTimestamp = $value;

        return $this;
    }

    public function setUpdateTimestamp(?int $value): ReviewInterface
    {
        $this->updateTimestamp = $value;

        return $this;
    }
}
