<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * One customs line in a `createReceiptShipment` body.
 */
interface ReceiptShipmentCustomsItemRequestInterface
{
    public function getCountryOfOrigin(): string;

    public function getDeclaredValue(): float;

    public function getHsCode(): string;

    public function setCountryOfOrigin(string $value): self;

    public function setDeclaredValue(float $value): self;

    public function setHsCode(string $value): self;
}
