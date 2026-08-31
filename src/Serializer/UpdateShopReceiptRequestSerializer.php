<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Http\FormValueEncoderInterface;
use ChristianBrown\Etsy\Model\UpdateShopReceiptRequestInterface;

final class UpdateShopReceiptRequestSerializer implements UpdateShopReceiptRequestSerializerInterface
{
    private FormValueEncoderInterface $formValueEncoder;

    public function __construct(FormValueEncoderInterface $formValueEncoder)
    {
        $this->formValueEncoder = $formValueEncoder;
    }

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopReceiptRequestInterface $updateShopReceiptRequest): array
    {
        $data = [];

        $data = $this->applyWasPaid($data, $updateShopReceiptRequest);
        $data = $this->applyWasShipped($data, $updateShopReceiptRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyWasPaid(array $data, UpdateShopReceiptRequestInterface $updateShopReceiptRequest): array
    {
        $value = $updateShopReceiptRequest->getWasPaid();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WAS_PAID] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, string> $data
     *
     * @return array<string, string>
     */
    private function applyWasShipped(array $data, UpdateShopReceiptRequestInterface $updateShopReceiptRequest): array
    {
        $value = $updateShopReceiptRequest->getWasShipped();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WAS_SHIPPED] = $this->formValueEncoder->encodeBool($value);

        return $data;
    }
}
