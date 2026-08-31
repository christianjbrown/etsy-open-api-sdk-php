<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `uploadListingImage` call. Either attach an `image` to upload new bytes, or
 * set `listingImageId` to attach an image the shop has already uploaded.
 */
interface UploadListingImageRequestInterface
{
    public function getAltText(): ?string;

    public function getImage(): ?MultipartFileInterface;

    public function getIsWatermarked(): ?bool;

    public function getListingImageId(): ?int;

    public function getOverwrite(): ?bool;

    public function getRank(): ?int;

    public function setAltText(?string $value): self;

    public function setImage(?MultipartFileInterface $value): self;

    public function setIsWatermarked(?bool $value): self;

    public function setListingImageId(?int $value): self;

    public function setOverwrite(?bool $value): self;

    public function setRank(?int $value): self;
}
