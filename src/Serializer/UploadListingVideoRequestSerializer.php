<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UploadListingVideoRequestInterface;

final class UploadListingVideoRequestSerializer implements UploadListingVideoRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UploadListingVideoRequestInterface $uploadListingVideoRequest): array
    {
        $data = [];

        $data = self::applyName($data, $uploadListingVideoRequest);
        $data = $this->applyVideoId($data, $uploadListingVideoRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyName(array $data, UploadListingVideoRequestInterface $uploadListingVideoRequest): array
    {
        $value = $uploadListingVideoRequest->getName();
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
    private function applyVideoId(array $data, UploadListingVideoRequestInterface $uploadListingVideoRequest): array
    {
        $value = $uploadListingVideoRequest->getVideoId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_VIDEO_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }
}
