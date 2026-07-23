<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopProductionPartner implements ShopProductionPartnerInterface
{
    private ?string $location = null;
    private ?string $partnerName = null;
    private int $productionPartnerId;

    public function __construct(int $productionPartnerId)
    {
        $this->productionPartnerId = $productionPartnerId;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getPartnerName(): ?string
    {
        return $this->partnerName;
    }

    public function getProductionPartnerId(): int
    {
        return $this->productionPartnerId;
    }

    public function setLocation(?string $value): ShopProductionPartnerInterface
    {
        $this->location = $value;

        return $this;
    }

    public function setPartnerName(?string $value): ShopProductionPartnerInterface
    {
        $this->partnerName = $value;

        return $this;
    }

    public function setProductionPartnerId(int $value): ShopProductionPartnerInterface
    {
        $this->productionPartnerId = $value;

        return $this;
    }
}
