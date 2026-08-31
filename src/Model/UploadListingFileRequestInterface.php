<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `uploadListingFile` call. Either attach a `file` to upload new bytes, or set
 * `listingFileId` to attach a file the shop has already uploaded.
 */
interface UploadListingFileRequestInterface
{
    public function getFile(): ?MultipartFileInterface;

    public function getListingFileId(): ?int;

    public function getName(): ?string;

    public function getRank(): ?int;

    public function setFile(?MultipartFileInterface $value): self;

    public function setListingFileId(?int $value): self;

    public function setName(?string $value): self;

    public function setRank(?int $value): self;
}
