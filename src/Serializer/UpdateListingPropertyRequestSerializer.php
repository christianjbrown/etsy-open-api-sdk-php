<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateListingPropertyRequestInterface;

use function array_merge;

final class UpdateListingPropertyRequestSerializer implements UpdateListingPropertyRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateListingPropertyRequestInterface $updateListingPropertyRequest): array
    {
        $data = [];

        $data = $this->applyScaleId($data, $updateListingPropertyRequest);
        $data = $this->applyValueIds($data, $updateListingPropertyRequest);
        $data = $this->applyValues($data, $updateListingPropertyRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyScaleId(array $data, UpdateListingPropertyRequestInterface $updateListingPropertyRequest): array
    {
        $value = $updateListingPropertyRequest->getScaleId();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SCALE_ID] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyValueIds(array $data, UpdateListingPropertyRequestInterface $updateListingPropertyRequest): array
    {
        return array_merge($data, $this->formValueEncoder->encodeIntList(self::KEY_VALUE_IDS, $updateListingPropertyRequest->getValueIds()));
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyValues(array $data, UpdateListingPropertyRequestInterface $updateListingPropertyRequest): array
    {
        return array_merge($data, $this->formValueEncoder->encodeStringList(self::KEY_VALUES, $updateListingPropertyRequest->getValues()));
    }
}
