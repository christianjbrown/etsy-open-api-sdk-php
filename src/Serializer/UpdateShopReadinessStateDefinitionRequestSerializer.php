<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopReadinessStateDefinitionRequestInterface;

final class UpdateShopReadinessStateDefinitionRequestSerializer implements UpdateShopReadinessStateDefinitionRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array
    {
        $data = [];

        $data = $this->applyMaxProcessingTime($data, $updateShopReadinessStateDefinitionRequest);
        $data = $this->applyMinProcessingTime($data, $updateShopReadinessStateDefinitionRequest);
        $data = self::applyProcessingTimeUnit($data, $updateShopReadinessStateDefinitionRequest);
        $data = self::applyReadinessState($data, $updateShopReadinessStateDefinitionRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMaxProcessingTime(array $data, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array
    {
        $value = $updateShopReadinessStateDefinitionRequest->getMaxProcessingTime();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAX_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMinProcessingTime(array $data, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array
    {
        $value = $updateShopReadinessStateDefinitionRequest->getMinProcessingTime();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MIN_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyProcessingTimeUnit(array $data, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array
    {
        $value = $updateShopReadinessStateDefinitionRequest->getProcessingTimeUnit();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_PROCESSING_TIME_UNIT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyReadinessState(array $data, UpdateShopReadinessStateDefinitionRequestInterface $updateShopReadinessStateDefinitionRequest): array
    {
        $value = $updateShopReadinessStateDefinitionRequest->getReadinessState();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_READINESS_STATE] = $value;

        return $data;
    }
}
