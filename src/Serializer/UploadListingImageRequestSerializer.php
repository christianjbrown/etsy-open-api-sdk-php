<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UploadListingImageRequestInterface;

final class UploadListingImageRequestSerializer implements UploadListingImageRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $data = [];

        $data = self::applyAltText($data, $uploadListingImageRequest);
        $data = $this->applyIsWatermarked($data, $uploadListingImageRequest);
        $data = $this->applyListingImageId($data, $uploadListingImageRequest);
        $data = $this->applyOverwrite($data, $uploadListingImageRequest);
        $data = $this->applyRank($data, $uploadListingImageRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyAltText(array $data, UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $value = $uploadListingImageRequest->getAltText();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_ALT_TEXT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyIsWatermarked(array $data, UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $value = $uploadListingImageRequest->getIsWatermarked();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_IS_WATERMARKED] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyListingImageId(array $data, UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $value = $uploadListingImageRequest->getListingImageId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_LISTING_IMAGE_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyOverwrite(array $data, UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $value = $uploadListingImageRequest->getOverwrite();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_OVERWRITE] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyRank(array $data, UploadListingImageRequestInterface $uploadListingImageRequest): array
    {
        $value = $uploadListingImageRequest->getRank();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_RANK] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
