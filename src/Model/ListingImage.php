<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingImage implements ListingImageInterface
{
    private ?string $altText = null;
    private ?int $blue = null;
    private ?int $brightness = null;
    private ?int $createdTimestamp = null;
    private ?int $creationTsz = null;
    private ?int $fullHeight = null;
    private ?int $fullWidth = null;
    private ?int $green = null;
    private ?string $hexCode = null;
    private ?int $hue = null;
    private ?bool $isBlackAndWhite = null;
    private ?int $listingId = null;
    private int $listingImageId;
    private ?int $rank = null;
    private ?int $red = null;
    private ?int $saturation = null;
    private ?string $url170x135 = null;
    private ?string $url570xN = null;
    private ?string $url75x75 = null;
    private ?string $urlFullxfull = null;

    public function __construct(int $listingImageId)
    {
        $this->listingImageId = $listingImageId;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function getBlue(): ?int
    {
        return $this->blue;
    }

    public function getBrightness(): ?int
    {
        return $this->brightness;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreationTsz(): ?int
    {
        return $this->creationTsz;
    }

    public function getFullHeight(): ?int
    {
        return $this->fullHeight;
    }

    public function getFullWidth(): ?int
    {
        return $this->fullWidth;
    }

    public function getGreen(): ?int
    {
        return $this->green;
    }

    public function getHexCode(): ?string
    {
        return $this->hexCode;
    }

    public function getHue(): ?int
    {
        return $this->hue;
    }

    public function getIsBlackAndWhite(): ?bool
    {
        return $this->isBlackAndWhite;
    }

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function getListingImageId(): int
    {
        return $this->listingImageId;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function getRed(): ?int
    {
        return $this->red;
    }

    public function getSaturation(): ?int
    {
        return $this->saturation;
    }

    public function getUrl170x135(): ?string
    {
        return $this->url170x135;
    }

    public function getUrl570xN(): ?string
    {
        return $this->url570xN;
    }

    public function getUrl75x75(): ?string
    {
        return $this->url75x75;
    }

    public function getUrlFullxfull(): ?string
    {
        return $this->urlFullxfull;
    }

    public function setAltText(?string $value): ListingImageInterface
    {
        $this->altText = $value;

        return $this;
    }

    public function setBlue(?int $value): ListingImageInterface
    {
        $this->blue = $value;

        return $this;
    }

    public function setBrightness(?int $value): ListingImageInterface
    {
        $this->brightness = $value;

        return $this;
    }

    public function setCreatedTimestamp(?int $value): ListingImageInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreationTsz(?int $value): ListingImageInterface
    {
        $this->creationTsz = $value;

        return $this;
    }

    public function setFullHeight(?int $value): ListingImageInterface
    {
        $this->fullHeight = $value;

        return $this;
    }

    public function setFullWidth(?int $value): ListingImageInterface
    {
        $this->fullWidth = $value;

        return $this;
    }

    public function setGreen(?int $value): ListingImageInterface
    {
        $this->green = $value;

        return $this;
    }

    public function setHexCode(?string $value): ListingImageInterface
    {
        $this->hexCode = $value;

        return $this;
    }

    public function setHue(?int $value): ListingImageInterface
    {
        $this->hue = $value;

        return $this;
    }

    public function setIsBlackAndWhite(?bool $value): ListingImageInterface
    {
        $this->isBlackAndWhite = $value;

        return $this;
    }

    public function setListingId(?int $value): ListingImageInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setListingImageId(int $value): ListingImageInterface
    {
        $this->listingImageId = $value;

        return $this;
    }

    public function setRank(?int $value): ListingImageInterface
    {
        $this->rank = $value;

        return $this;
    }

    public function setRed(?int $value): ListingImageInterface
    {
        $this->red = $value;

        return $this;
    }

    public function setSaturation(?int $value): ListingImageInterface
    {
        $this->saturation = $value;

        return $this;
    }

    public function setUrl170x135(?string $value): ListingImageInterface
    {
        $this->url170x135 = $value;

        return $this;
    }

    public function setUrl570xN(?string $value): ListingImageInterface
    {
        $this->url570xN = $value;

        return $this;
    }

    public function setUrl75x75(?string $value): ListingImageInterface
    {
        $this->url75x75 = $value;

        return $this;
    }

    public function setUrlFullxfull(?string $value): ListingImageInterface
    {
        $this->urlFullxfull = $value;

        return $this;
    }
}
