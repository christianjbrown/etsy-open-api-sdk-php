<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `uploadListingVideo` call. Either attach a `video` to upload new bytes, or set
 * `videoId` to attach a video the shop has already uploaded.
 */
interface UploadListingVideoRequestInterface
{
    public function getName(): ?string;

    public function getVideo(): ?MultipartFileInterface;

    public function getVideoId(): ?int;

    public function setName(?string $value): self;

    public function setVideo(?MultipartFileInterface $value): self;

    public function setVideoId(?int $value): self;
}
