<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShippingCarrier implements ShippingCarrierInterface
{
    /**
     * @var array<int, ShippingCarrierMailClassInterface>
     */
    private array $domesticClasses = [];

    /**
     * @var array<int, ShippingCarrierMailClassInterface>
     */
    private array $internationalClasses = [];
    private ?string $name = null;
    private int $shippingCarrierId;

    public function __construct(int $shippingCarrierId)
    {
        $this->shippingCarrierId = $shippingCarrierId;
    }

    /**
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function getDomesticClasses(): array
    {
        return $this->domesticClasses;
    }

    /**
     * @return array<int, ShippingCarrierMailClassInterface>
     */
    public function getInternationalClasses(): array
    {
        return $this->internationalClasses;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getShippingCarrierId(): int
    {
        return $this->shippingCarrierId;
    }

    /**
     * @param array<int, ShippingCarrierMailClassInterface> $value
     */
    public function setDomesticClasses(array $value): ShippingCarrierInterface
    {
        $this->domesticClasses = $value;

        return $this;
    }

    /**
     * @param array<int, ShippingCarrierMailClassInterface> $value
     */
    public function setInternationalClasses(array $value): ShippingCarrierInterface
    {
        $this->internationalClasses = $value;

        return $this;
    }

    public function setName(?string $value): ShippingCarrierInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setShippingCarrierId(int $value): ShippingCarrierInterface
    {
        $this->shippingCarrierId = $value;

        return $this;
    }
}
