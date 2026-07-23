<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingFileInterface
{
    public function getCreatedTimestamp(): ?int;

    public function getCreateTimestamp(): ?int;

    public function getFilename(): ?string;

    public function getFilesize(): ?string;

    public function getFiletype(): ?string;

    public function getListingFileId(): int;

    public function getListingId(): ?int;

    public function getRank(): ?int;

    public function getSizeBytes(): ?int;

    public function setCreatedTimestamp(?int $value): self;

    public function setCreateTimestamp(?int $value): self;

    public function setFilename(?string $value): self;

    public function setFilesize(?string $value): self;

    public function setFiletype(?string $value): self;

    public function setListingFileId(int $value): self;

    public function setListingId(?int $value): self;

    public function setRank(?int $value): self;

    public function setSizeBytes(?int $value): self;
}
