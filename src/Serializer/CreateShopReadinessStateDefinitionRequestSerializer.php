<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\CreateShopReadinessStateDefinitionRequestInterface;

final class CreateShopReadinessStateDefinitionRequestSerializer implements CreateShopReadinessStateDefinitionRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): array
    {
        $data = [];

        $data = $this->applyMaxProcessingTime($data, $createShopReadinessStateDefinitionRequest);
        $data = $this->applyMinProcessingTime($data, $createShopReadinessStateDefinitionRequest);
        $data = self::applyProcessingTimeUnit($data, $createShopReadinessStateDefinitionRequest);
        $data = self::applyReadinessState($data, $createShopReadinessStateDefinitionRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMaxProcessingTime(array $data, CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): array
    {
        $data[self::KEY_MAX_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($createShopReadinessStateDefinitionRequest->getMaxProcessingTime());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyMinProcessingTime(array $data, CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): array
    {
        $data[self::KEY_MIN_PROCESSING_TIME] = $this->formValueEncoder->encodeInt($createShopReadinessStateDefinitionRequest->getMinProcessingTime());

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private static function applyProcessingTimeUnit(array $data, CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): array
    {
        $value = $createShopReadinessStateDefinitionRequest->getProcessingTimeUnit();
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
    private static function applyReadinessState(array $data, CreateShopReadinessStateDefinitionRequestInterface $createShopReadinessStateDefinitionRequest): array
    {
        $data[self::KEY_READINESS_STATE] = $createShopReadinessStateDefinitionRequest->getReadinessState();

        return $data;
    }
}
