<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopProductionPartnerInterface
{
    public function getLocation(): ?string;

    public function getPartnerName(): ?string;

    public function getProductionPartnerId(): int;

    public function setLocation(?string $value): self;

    public function setPartnerName(?string $value): self;

    public function setProductionPartnerId(int $value): self;
}
