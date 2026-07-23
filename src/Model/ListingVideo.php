<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ListingVideo implements ListingVideoInterface
{
    private ?int $height = null;
    private ?string $thumbnailUrl = null;
    private int $videoId;
    private ?string $videoState = null;
    private ?string $videoUrl = null;
    private ?int $width = null;

    public function __construct(int $videoId)
    {
        $this->videoId = $videoId;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function getVideoId(): int
    {
        return $this->videoId;
    }

    public function getVideoState(): ?string
    {
        return $this->videoState;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setHeight(?int $value): ListingVideoInterface
    {
        $this->height = $value;

        return $this;
    }

    public function setThumbnailUrl(?string $value): ListingVideoInterface
    {
        $this->thumbnailUrl = $value;

        return $this;
    }

    public function setVideoId(int $value): ListingVideoInterface
    {
        $this->videoId = $value;

        return $this;
    }

    public function setVideoState(?string $value): ListingVideoInterface
    {
        $this->videoState = $value;

        return $this;
    }

    public function setVideoUrl(?string $value): ListingVideoInterface
    {
        $this->videoUrl = $value;

        return $this;
    }

    public function setWidth(?int $value): ListingVideoInterface
    {
        $this->width = $value;

        return $this;
    }
}
