<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingImageInterface
{
    public function getAltText(): ?string;

    public function getBlue(): ?int;

    public function getBrightness(): ?int;

    public function getCreatedTimestamp(): ?int;

    public function getCreationTsz(): ?int;

    public function getFullHeight(): ?int;

    public function getFullWidth(): ?int;

    public function getGreen(): ?int;

    public function getHexCode(): ?string;

    public function getHue(): ?int;

    public function getIsBlackAndWhite(): ?bool;

    public function getListingId(): ?int;

    public function getListingImageId(): int;

    public function getRank(): ?int;

    public function getRed(): ?int;

    public function getSaturation(): ?int;

    public function getUrl170x135(): ?string;

    public function getUrl570xN(): ?string;

    public function getUrl75x75(): ?string;

    public function getUrlFullxfull(): ?string;

    public function setAltText(?string $value): self;

    public function setBlue(?int $value): self;

    public function setBrightness(?int $value): self;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreationTsz(?int $value): self;

    public function setFullHeight(?int $value): self;

    public function setFullWidth(?int $value): self;

    public function setGreen(?int $value): self;

    public function setHexCode(?string $value): self;

    public function setHue(?int $value): self;

    public function setIsBlackAndWhite(?bool $value): self;

    public function setListingId(?int $value): self;

    public function setListingImageId(int $value): self;

    public function setRank(?int $value): self;

    public function setRed(?int $value): self;

    public function setSaturation(?int $value): self;

    public function setUrl170x135(?string $value): self;

    public function setUrl570xN(?string $value): self;

    public function setUrl75x75(?string $value): self;

    public function setUrlFullxfull(?string $value): self;
}
