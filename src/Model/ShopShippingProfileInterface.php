<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopShippingProfileInterface
{
    public function getDomesticHandlingFee(): ?float;

    public function getInternationalHandlingFee(): ?float;

    public function getIsDeleted(): ?bool;

    public function getOriginCountryIso(): ?string;

    public function getOriginPostalCode(): ?string;

    public function getProfileType(): ?string;

    /**
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function getShippingProfileDestinations(): array;

    public function getShippingProfileId(): int;

    /**
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function getShippingProfileUpgrades(): array;

    public function getTitle(): ?string;

    public function getUserId(): ?int;

    public function setDomesticHandlingFee(?float $value): self;

    public function setInternationalHandlingFee(?float $value): self;

    public function setIsDeleted(?bool $value): self;

    public function setOriginCountryIso(?string $value): self;

    public function setOriginPostalCode(?string $value): self;

    public function setProfileType(?string $value): self;

    /**
     * @param array<int, ShopShippingProfileDestinationInterface> $value
     */
    public function setShippingProfileDestinations(array $value): self;

    public function setShippingProfileId(int $value): self;

    /**
     * @param array<int, ShopShippingProfileUpgradeInterface> $value
     */
    public function setShippingProfileUpgrades(array $value): self;

    public function setTitle(?string $value): self;

    public function setUserId(?int $value): self;
}
