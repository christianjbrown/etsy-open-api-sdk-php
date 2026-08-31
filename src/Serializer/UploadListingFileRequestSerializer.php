<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UploadListingFileRequestInterface;

final class UploadListingFileRequestSerializer implements UploadListingFileRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingFileRequestInterface $uploadListingFileRequest): array
    {
        $data = [];

        $data = $this->applyListingFileId($data, $uploadListingFileRequest);
        $data = self::applyName($data, $uploadListingFileRequest);
        $data = $this->applyRank($data, $uploadListingFileRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyListingFileId(array $data, UploadListingFileRequestInterface $uploadListingFileRequest): array
    {
        $value = $uploadListingFileRequest->getListingFileId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_LISTING_FILE_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyName(array $data, UploadListingFileRequestInterface $uploadListingFileRequest): array
    {
        $value = $uploadListingFileRequest->getName();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_NAME] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyRank(array $data, UploadListingFileRequestInterface $uploadListingFileRequest): array
    {
        $value = $uploadListingFileRequest->getRank();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_RANK] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
