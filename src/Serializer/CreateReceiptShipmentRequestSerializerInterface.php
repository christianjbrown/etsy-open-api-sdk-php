<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\CreateReceiptShipmentRequestInterface;

interface CreateReceiptShipmentRequestSerializerInterface
{
    public const string KEY_CARRIER_NAME = 'carrier_name';
    public const string KEY_CUSTOMS_DATA = 'customs_data';
    public const string KEY_DIMENSION_UNITS = 'dimension_units';
    public const string KEY_DUTY_AMOUNT = 'duty_amount';
    public const string KEY_DUTY_CURRENCY = 'duty_currency';
    public const string KEY_HEIGHT = 'height';
    public const string KEY_INCOTERM = 'incoterm';
    public const string KEY_LENGTH = 'length';
    public const string KEY_MAIL_CLASS = 'mail_class';
    public const string KEY_NOTE_TO_BUYER = 'note_to_buyer';
    public const string KEY_REVENUE_ELIGIBILITY = 'revenue_eligibility';
    public const string KEY_SEND_BCC = 'send_bcc';
    public const string KEY_SHIP_DATE = 'ship_date';
    public const string KEY_SHIP_FROM_COUNTRY = 'ship_from_country';
    public const string KEY_SHIP_TO_COUNTRY = 'ship_to_country';
    public const string KEY_SHIPPING_LABEL_COST = 'shipping_label_cost';
    public const string KEY_SHIPPING_LABEL_CURRENCY = 'shipping_label_currency';
    public const string KEY_TRACKING_CODE = 'tracking_code';
    public const string KEY_WEIGHT = 'weight';
    public const string KEY_WEIGHT_UNITS = 'weight_units';
    public const string KEY_WIDTH = 'width';

    /**
     * @return array<string, mixed>
     */
    public function serialize(CreateReceiptShipmentRequestInterface $createReceiptShipmentRequest): array;
}
