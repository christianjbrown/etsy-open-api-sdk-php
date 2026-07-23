<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShippingCarrierMailClassInterface
{
    public function getMailClassKey(): ?string;

    public function getName(): ?string;

    public function setMailClassKey(?string $value): self;

    public function setName(?string $value): self;
}
