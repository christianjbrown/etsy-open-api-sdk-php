<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\ListingTranslationRequestInterface;

use function array_merge;

final class ListingTranslationRequestSerializer implements ListingTranslationRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(ListingTranslationRequestInterface $listingTranslationRequest): array
    {
        $data = [];

        $data = self::applyDescription($data, $listingTranslationRequest);
        $data = $this->applyTags($data, $listingTranslationRequest);
        $data = self::applyTitle($data, $listingTranslationRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyDescription(array $data, ListingTranslationRequestInterface $listingTranslationRequest): array
    {
        $data[self::KEY_DESCRIPTION] = $listingTranslationRequest->getDescription();

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyTags(array $data, ListingTranslationRequestInterface $listingTranslationRequest): array
    {
        $value = $listingTranslationRequest->getTags();
        if (empty($value)) {
            return $data;
        }

        return array_merge($data, $this->formValueEncoder->encodeStringList(self::KEY_TAGS, $value));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyTitle(array $data, ListingTranslationRequestInterface $listingTranslationRequest): array
    {
        $data[self::KEY_TITLE] = $listingTranslationRequest->getTitle();

        return $data;
    }
}
