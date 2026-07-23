<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UserAddress implements UserAddressInterface
{
    private ?string $city = null;
    private ?string $countryName = null;
    private ?string $firstLine = null;
    private ?bool $isDefaultShippingAddress = null;
    private ?string $isoCountryCode = null;
    private ?string $name = null;
    private ?string $secondLine = null;
    private ?string $state = null;
    private int $userAddressId;
    private ?int $userId = null;
    private ?string $zip = null;

    public function __construct(int $userAddressId)
    {
        $this->userAddressId = $userAddressId;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function getFirstLine(): ?string
    {
        return $this->firstLine;
    }

    public function getIsDefaultShippingAddress(): ?bool
    {
        return $this->isDefaultShippingAddress;
    }

    public function getIsoCountryCode(): ?string
    {
        return $this->isoCountryCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSecondLine(): ?string
    {
        return $this->secondLine;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getUserAddressId(): int
    {
        return $this->userAddressId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setCity(?string $value): UserAddressInterface
    {
        $this->city = $value;

        return $this;
    }

    public function setCountryName(?string $value): UserAddressInterface
    {
        $this->countryName = $value;

        return $this;
    }

    public function setFirstLine(?string $value): UserAddressInterface
    {
        $this->firstLine = $value;

        return $this;
    }

    public function setIsDefaultShippingAddress(?bool $value): UserAddressInterface
    {
        $this->isDefaultShippingAddress = $value;

        return $this;
    }

    public function setIsoCountryCode(?string $value): UserAddressInterface
    {
        $this->isoCountryCode = $value;

        return $this;
    }

    public function setName(?string $value): UserAddressInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setSecondLine(?string $value): UserAddressInterface
    {
        $this->secondLine = $value;

        return $this;
    }

    public function setState(?string $value): UserAddressInterface
    {
        $this->state = $value;

        return $this;
    }

    public function setUserAddressId(int $value): UserAddressInterface
    {
        $this->userAddressId = $value;

        return $this;
    }

    public function setUserId(?int $value): UserAddressInterface
    {
        $this->userId = $value;

        return $this;
    }

    public function setZip(?string $value): UserAddressInterface
    {
        $this->zip = $value;

        return $this;
    }
}
