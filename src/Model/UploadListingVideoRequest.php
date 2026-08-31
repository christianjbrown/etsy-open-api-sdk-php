<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UploadListingVideoRequest implements UploadListingVideoRequestInterface
{
    private ?string $name = null;
    private ?MultipartFileInterface $video = null;
    private ?int $videoId = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getVideo(): ?MultipartFileInterface
    {
        return $this->video;
    }

    public function getVideoId(): ?int
    {
        return $this->videoId;
    }

    public function setName(?string $value): UploadListingVideoRequestInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setVideo(?MultipartFileInterface $value): UploadListingVideoRequestInterface
    {
        $this->video = $value;

        return $this;
    }

    public function setVideoId(?int $value): UploadListingVideoRequestInterface
    {
        $this->videoId = $value;

        return $this;
    }
}
