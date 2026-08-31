<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ReceiptShipmentCustomsItemRequest implements ReceiptShipmentCustomsItemRequestInterface
{
    private string $countryOfOrigin;
    private float $declaredValue;
    private string $hsCode;

    public function __construct(string $countryOfOrigin, float $declaredValue, string $hsCode)
    {
        $this->countryOfOrigin = $countryOfOrigin;
        $this->declaredValue = $declaredValue;
        $this->hsCode = $hsCode;
    }

    public function getCountryOfOrigin(): string
    {
        return $this->countryOfOrigin;
    }

    public function getDeclaredValue(): float
    {
        return $this->declaredValue;
    }

    public function getHsCode(): string
    {
        return $this->hsCode;
    }

    public function setCountryOfOrigin(string $value): ReceiptShipmentCustomsItemRequestInterface
    {
        $this->countryOfOrigin = $value;

        return $this;
    }

    public function setDeclaredValue(float $value): ReceiptShipmentCustomsItemRequestInterface
    {
        $this->declaredValue = $value;

        return $this;
    }

    public function setHsCode(string $value): ReceiptShipmentCustomsItemRequestInterface
    {
        $this->hsCode = $value;

        return $this;
    }
}
