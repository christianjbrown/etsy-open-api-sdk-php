<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateShop` call.
 */
interface UpdateShopRequestInterface
{
    public function getAnnouncement(): ?string;

    public function getDigitalSaleMessage(): ?string;

    public function getPolicyAdditional(): ?string;

    public function getSaleMessage(): ?string;

    public function getTitle(): ?string;

    public function setAnnouncement(?string $value): self;

    public function setDigitalSaleMessage(?string $value): self;

    public function setPolicyAdditional(?string $value): self;

    public function setSaleMessage(?string $value): self;

    public function setTitle(?string $value): self;
}
