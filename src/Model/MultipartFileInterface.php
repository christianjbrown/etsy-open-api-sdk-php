<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One file part of a `multipart/form-data` upload body. The field name is Etsy's, and differs per
 * endpoint: `image` for a listing image, `file` for a digital download, `video` for a listing video.
 */
interface MultipartFileInterface
{
    public function getContents(): string;

    public function getContentType(): string;

    public function getFieldName(): string;

    public function getFileName(): string;

    public function setContents(string $value): self;

    public function setContentType(string $value): self;

    public function setFieldName(string $value): self;

    public function setFileName(string $value): self;
}
