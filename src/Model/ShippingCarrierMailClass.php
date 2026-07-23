<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShippingCarrierMailClass implements ShippingCarrierMailClassInterface
{
    private ?string $mailClassKey = null;
    private ?string $name = null;

    public function getMailClassKey(): ?string
    {
        return $this->mailClassKey;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setMailClassKey(?string $value): ShippingCarrierMailClassInterface
    {
        $this->mailClassKey = $value;

        return $this;
    }

    public function setName(?string $value): ShippingCarrierMailClassInterface
    {
        $this->name = $value;

        return $this;
    }
}
