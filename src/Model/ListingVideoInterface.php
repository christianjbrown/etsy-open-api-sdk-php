<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ListingVideoInterface
{
    public function getHeight(): ?int;

    public function getThumbnailUrl(): ?string;

    public function getVideoId(): int;

    public function getVideoState(): ?string;

    public function getVideoUrl(): ?string;

    public function getWidth(): ?int;

    public function setHeight(?int $value): self;

    public function setThumbnailUrl(?string $value): self;

    public function setVideoId(int $value): self;

    public function setVideoState(?string $value): self;

    public function setVideoUrl(?string $value): self;

    public function setWidth(?int $value): self;
}
