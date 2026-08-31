<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequestInterface;

final class CreateReceiptShipmentRequestSerializer implements CreateReceiptShipmentRequestSerializerInterface
{
    private ReceiptShipmentCustomsItemRequestsSerializerInterface $receiptShipmentCustomsItemRequestsSerializer;

    public function __construct(ReceiptShipmentCustomsItemRequestsSerializerInterface $receiptShipmentCustomsItemRequestsSerializer)
    {
        $this->receiptShipmentCustomsItemRequestsSerializer = $receiptShipmentCustomsItemRequestsSerializer;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $data = [];

        $data = self::applyCarrierName($data, $createReceiptShipmentRequest);
        $data = $this->applyCustomsData($data, $createReceiptShipmentRequest);
        $data = self::applyDimensionUnits($data, $createReceiptShipmentRequest);
        $data = self::applyDutyAmount($data, $createReceiptShipmentRequest);
        $data = self::applyDutyCurrency($data, $createReceiptShipmentRequest);
        $data = self::applyHeight($data, $createReceiptShipmentRequest);
        $data = self::applyIncoterm($data, $createReceiptShipmentRequest);
        $data = self::applyLength($data, $createReceiptShipmentRequest);
        $data = self::applyMailClass($data, $createReceiptShipmentRequest);
        $data = self::applyNoteToBuyer($data, $createReceiptShipmentRequest);
        $data = self::applyRevenueEligibility($data, $createReceiptShipmentRequest);
        $data = self::applySendBcc($data, $createReceiptShipmentRequest);
        $data = self::applyShipDate($data, $createReceiptShipmentRequest);
        $data = self::applyShipFromCountry($data, $createReceiptShipmentRequest);
        $data = self::applyShippingLabelCost($data, $createReceiptShipmentRequest);
        $data = self::applyShippingLabelCurrency($data, $createReceiptShipmentRequest);
        $data = self::applyShipToCountry($data, $createReceiptShipmentRequest);
        $data = self::applyTrackingCode($data, $createReceiptShipmentRequest);
        $data = self::applyWeight($data, $createReceiptShipmentRequest);
        $data = self::applyWeightUnits($data, $createReceiptShipmentRequest);
        $data = self::applyWidth($data, $createReceiptShipmentRequest);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyCarrierName(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getCarrierName();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_CARRIER_NAME] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function applyCustomsData(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getCustomsData();
        if (empty($value)) {
            return $data;
        }
        $data[self::KEY_CUSTOMS_DATA] = $this->receiptShipmentCustomsItemRequestsSerializer->serialize($value);

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyDimensionUnits(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getDimensionUnits();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DIMENSION_UNITS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyDutyAmount(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getDutyAmount();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DUTY_AMOUNT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyDutyCurrency(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getDutyCurrency();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_DUTY_CURRENCY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyHeight(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getHeight();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_HEIGHT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyIncoterm(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getIncoterm();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_INCOTERM] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyLength(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getLength();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_LENGTH] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyMailClass(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getMailClass();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_MAIL_CLASS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyNoteToBuyer(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getNoteToBuyer();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_NOTE_TO_BUYER] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyRevenueEligibility(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getRevenueEligibility();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_REVENUE_ELIGIBILITY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applySendBcc(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getSendBcc();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SEND_BCC] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyShipDate(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getShipDate();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIP_DATE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyShipFromCountry(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getShipFromCountry();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIP_FROM_COUNTRY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyShippingLabelCost(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getShippingLabelCost();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_LABEL_COST] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyShippingLabelCurrency(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getShippingLabelCurrency();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIPPING_LABEL_CURRENCY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyShipToCountry(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getShipToCountry();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_SHIP_TO_COUNTRY] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyTrackingCode(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getTrackingCode();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_TRACKING_CODE] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyWeight(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getWeight();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WEIGHT] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyWeightUnits(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getWeightUnits();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WEIGHT_UNITS] = $value;

        return $data;
    }

    /**
     * @phpstan-param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private static function applyWidth(array $data, CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array
    {
        $value = $createReceiptShipmentRequest->getWidth();
        if (null === $value) {
            return $data;
        }
        $data[self::KEY_WIDTH] = $value;

        return $data;
    }
}
