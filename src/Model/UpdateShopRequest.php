<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopRequest implements UpdateShopRequestInterface
{
    private ?string $announcement = null;
    private ?string $digitalSaleMessage = null;
    private ?string $policyAdditional = null;
    private ?string $saleMessage = null;
    private ?string $title = null;

    public function getAnnouncement(): ?string
    {
        return $this->announcement;
    }

    public function getDigitalSaleMessage(): ?string
    {
        return $this->digitalSaleMessage;
    }

    public function getPolicyAdditional(): ?string
    {
        return $this->policyAdditional;
    }

    public function getSaleMessage(): ?string
    {
        return $this->saleMessage;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setAnnouncement(?string $value): UpdateShopRequestInterface
    {
        $this->announcement = $value;

        return $this;
    }

    public function setDigitalSaleMessage(?string $value): UpdateShopRequestInterface
    {
        $this->digitalSaleMessage = $value;

        return $this;
    }

    public function setPolicyAdditional(?string $value): UpdateShopRequestInterface
    {
        $this->policyAdditional = $value;

        return $this;
    }

    public function setSaleMessage(?string $value): UpdateShopRequestInterface
    {
        $this->saleMessage = $value;

        return $this;
    }

    public function setTitle(?string $value): UpdateShopRequestInterface
    {
        $this->title = $value;

        return $this;
    }
}
