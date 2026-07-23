<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopHolidayPreference implements ShopHolidayPreferenceInterface
{
    private ?string $countryIso = null;
    private ?int $holidayId = null;
    private ?string $holidayName = null;
    private ?bool $isWorking = null;
    private ?int $shopId = null;

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function getHolidayId(): ?int
    {
        return $this->holidayId;
    }

    public function getHolidayName(): ?string
    {
        return $this->holidayName;
    }

    public function getIsWorking(): ?bool
    {
        return $this->isWorking;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setCountryIso(?string $value): ShopHolidayPreferenceInterface
    {
        $this->countryIso = $value;

        return $this;
    }

    public function setHolidayId(?int $value): ShopHolidayPreferenceInterface
    {
        $this->holidayId = $value;

        return $this;
    }

    public function setHolidayName(?string $value): ShopHolidayPreferenceInterface
    {
        $this->holidayName = $value;

        return $this;
    }

    public function setIsWorking(?bool $value): ShopHolidayPreferenceInterface
    {
        $this->isWorking = $value;

        return $this;
    }

    public function setShopId(?int $value): ShopHolidayPreferenceInterface
    {
        $this->shopId = $value;

        return $this;
    }
}
