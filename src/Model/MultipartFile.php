<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class MultipartFile implements MultipartFileInterface
{
    private string $contents;
    private string $contentType;
    private string $fieldName;
    private string $fileName;

    public function __construct(string $fieldName, string $fileName, string $contentType, string $contents)
    {
        $this->fieldName = $fieldName;
        $this->fileName = $fileName;
        $this->contentType = $contentType;
        $this->contents = $contents;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setContents(string $value): MultipartFileInterface
    {
        $this->contents = $value;

        return $this;
    }

    public function setContentType(string $value): MultipartFileInterface
    {
        $this->contentType = $value;

        return $this;
    }

    public function setFieldName(string $value): MultipartFileInterface
    {
        $this->fieldName = $value;

        return $this;
    }

    public function setFileName(string $value): MultipartFileInterface
    {
        $this->fileName = $value;

        return $this;
    }
}
