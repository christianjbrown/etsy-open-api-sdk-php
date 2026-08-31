<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UploadListingImageRequest implements UploadListingImageRequestInterface
{
    private ?string $altText = null;
    private ?MultipartFileInterface $image = null;
    private ?bool $isWatermarked = null;
    private ?int $listingImageId = null;
    private ?bool $overwrite = null;
    private ?int $rank = null;

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function getImage(): ?MultipartFileInterface
    {
        return $this->image;
    }

    public function getIsWatermarked(): ?bool
    {
        return $this->isWatermarked;
    }

    public function getListingImageId(): ?int
    {
        return $this->listingImageId;
    }

    public function getOverwrite(): ?bool
    {
        return $this->overwrite;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setAltText(?string $value): UploadListingImageRequestInterface
    {
        $this->altText = $value;

        return $this;
    }

    public function setImage(?MultipartFileInterface $value): UploadListingImageRequestInterface
    {
        $this->image = $value;

        return $this;
    }

    public function setIsWatermarked(?bool $value): UploadListingImageRequestInterface
    {
        $this->isWatermarked = $value;

        return $this;
    }

    public function setListingImageId(?int $value): UploadListingImageRequestInterface
    {
        $this->listingImageId = $value;

        return $this;
    }

    public function setOverwrite(?bool $value): UploadListingImageRequestInterface
    {
        $this->overwrite = $value;

        return $this;
    }

    public function setRank(?int $value): UploadListingImageRequestInterface
    {
        $this->rank = $value;

        return $this;
    }
}
