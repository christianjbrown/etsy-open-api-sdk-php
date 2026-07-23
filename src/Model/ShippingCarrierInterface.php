<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShippingCarrierInterface
{
    /**
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function getDomesticClasses(): array;

    /**
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function getInternationalClasses(): array;

    public function getName(): ?string;

    public function getShippingCarrierId(): int;

    /**
     * @param array<int, ShippingCarrierMailClassInterface> $value
     */
    public function setDomesticClasses(array $value): self;

    /**
     * @param array<int, ShippingCarrierMailClassInterface> $value
     */
    public function setInternationalClasses(array $value): self;

    public function setName(?string $value): self;

    public function setShippingCarrierId(int $value): self;
}
