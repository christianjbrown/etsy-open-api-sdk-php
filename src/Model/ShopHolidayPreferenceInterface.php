<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopHolidayPreferenceInterface
{
    public function getCountryIso(): ?string;

    public function getHolidayId(): ?int;

    public function getHolidayName(): ?string;

    public function getIsWorking(): ?bool;

    public function getShopId(): ?int;

    public function setCountryIso(?string $value): self;

    public function setHolidayId(?int $value): self;

    public function setHolidayName(?string $value): self;

    public function setIsWorking(?bool $value): self;

    public function setShopId(?int $value): self;
}
