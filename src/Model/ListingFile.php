<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingFile implements ListingFileInterface
{
    private ?int $createdTimestamp = null;
    private ?int $createTimestamp = null;
    private ?string $filename = null;
    private ?string $filesize = null;
    private ?string $filetype = null;
    private int $listingFileId;
    private ?int $listingId = null;
    private ?int $rank = null;
    private ?int $sizeBytes = null;

    public function __construct(int $listingFileId)
    {
        $this->listingFileId = $listingFileId;
    }

    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    public function getCreateTimestamp(): ?int
    {
        return $this->createTimestamp;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getFilesize(): ?string
    {
        return $this->filesize;
    }

    public function getFiletype(): ?string
    {
        return $this->filetype;
    }

    public function getListingFileId(): int
    {
        return $this->listingFileId;
    }

    public function getListingId(): ?int
    {
        return $this->listingId;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function getSizeBytes(): ?int
    {
        return $this->sizeBytes;
    }

    public function setCreatedTimestamp(?int $value): ListingFileInterface
    {
        $this->createdTimestamp = $value;

        return $this;
    }

    public function setCreateTimestamp(?int $value): ListingFileInterface
    {
        $this->createTimestamp = $value;

        return $this;
    }

    public function setFilename(?string $value): ListingFileInterface
    {
        $this->filename = $value;

        return $this;
    }

    public function setFilesize(?string $value): ListingFileInterface
    {
        $this->filesize = $value;

        return $this;
    }

    public function setFiletype(?string $value): ListingFileInterface
    {
        $this->filetype = $value;

        return $this;
    }

    public function setListingFileId(int $value): ListingFileInterface
    {
        $this->listingFileId = $value;

        return $this;
    }

    public function setListingId(?int $value): ListingFileInterface
    {
        $this->listingId = $value;

        return $this;
    }

    public function setRank(?int $value): ListingFileInterface
    {
        $this->rank = $value;

        return $this;
    }

    public function setSizeBytes(?int $value): ListingFileInterface
    {
        $this->sizeBytes = $value;

        return $this;
    }
}
