<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface UserAddressInterface
{
    public function getCity(): ?string;

    public function getCountryName(): ?string;

    public function getFirstLine(): ?string;

    public function getIsDefaultShippingAddress(): ?bool;

    public function getIsoCountryCode(): ?string;

    public function getName(): ?string;

    public function getSecondLine(): ?string;

    public function getState(): ?string;

    public function getUserAddressId(): int;

    public function getUserId(): ?int;

    public function getZip(): ?string;

    public function setCity(?string $value): self;

    public function setCountryName(?string $value): self;

    public function setFirstLine(?string $value): self;

    public function setIsDefaultShippingAddress(?bool $value): self;

    public function setIsoCountryCode(?string $value): self;

    public function setName(?string $value): self;

    public function setSecondLine(?string $value): self;

    public function setState(?string $value): self;

    public function setUserAddressId(int $value): self;

    public function setUserId(?int $value): self;

    public function setZip(?string $value): self;
}
