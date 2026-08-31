<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UploadListingFileRequest implements UploadListingFileRequestInterface
{
    private ?MultipartFileInterface $file = null;
    private ?int $listingFileId = null;
    private ?string $name = null;
    private ?int $rank = null;

    public function getFile(): ?MultipartFileInterface
    {
        return $this->file;
    }

    public function getListingFileId(): ?int
    {
        return $this->listingFileId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setFile(?MultipartFileInterface $value): UploadListingFileRequestInterface
    {
        $this->file = $value;

        return $this;
    }

    public function setListingFileId(?int $value): UploadListingFileRequestInterface
    {
        $this->listingFileId = $value;

        return $this;
    }

    public function setName(?string $value): UploadListingFileRequestInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setRank(?int $value): UploadListingFileRequestInterface
    {
        $this->rank = $value;

        return $this;
    }
}
