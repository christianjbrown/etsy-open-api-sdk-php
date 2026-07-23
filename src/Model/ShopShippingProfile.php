<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopShippingProfile implements ShopShippingProfileInterface
{
    private ?float $domesticHandlingFee = null;
    private ?float $internationalHandlingFee = null;
    private ?bool $isDeleted = null;
    private ?string $originCountryIso = null;
    private ?string $originPostalCode = null;
    private ?string $profileType = null;

    /**
     * @var array<int, ShopShippingProfileDestinationInterface>
     */
    private array $shippingProfileDestinations = [];
    private int $shippingProfileId;

    /**
     * @var array<int, ShopShippingProfileUpgradeInterface>
     */
    private array $shippingProfileUpgrades = [];
    private ?string $title = null;
    private ?int $userId = null;

    public function __construct(int $shippingProfileId)
    {
        $this->shippingProfileId = $shippingProfileId;
    }

    public function getDomesticHandlingFee(): ?float
    {
        return $this->domesticHandlingFee;
    }

    public function getInternationalHandlingFee(): ?float
    {
        return $this->internationalHandlingFee;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function getOriginCountryIso(): ?string
    {
        return $this->originCountryIso;
    }

    public function getOriginPostalCode(): ?string
    {
        return $this->originPostalCode;
    }

    public function getProfileType(): ?string
    {
        return $this->profileType;
    }

    /**
     * @return array<int, ShopShippingProfileDestinationInterface>
     */
    public function getShippingProfileDestinations(): array
    {
        return $this->shippingProfileDestinations;
    }

    public function getShippingProfileId(): int
    {
        return $this->shippingProfileId;
    }

    /**
     * @return array<int, ShopShippingProfileUpgradeInterface>
     */
    public function getShippingProfileUpgrades(): array
    {
        return $this->shippingProfileUpgrades;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setDomesticHandlingFee(?float $value): ShopShippingProfileInterface
    {
        $this->domesticHandlingFee = $value;

        return $this;
    }

    public function setInternationalHandlingFee(?float $value): ShopShippingProfileInterface
    {
        $this->internationalHandlingFee = $value;

        return $this;
    }

    public function setIsDeleted(?bool $value): ShopShippingProfileInterface
    {
        $this->isDeleted = $value;

        return $this;
    }

    public function setOriginCountryIso(?string $value): ShopShippingProfileInterface
    {
        $this->originCountryIso = $value;

        return $this;
    }

    public function setOriginPostalCode(?string $value): ShopShippingProfileInterface
    {
        $this->originPostalCode = $value;

        return $this;
    }

    public function setProfileType(?string $value): ShopShippingProfileInterface
    {
        $this->profileType = $value;

        return $this;
    }

    /**
     * @param array<int, ShopShippingProfileDestinationInterface> $value
     */
    public function setShippingProfileDestinations(array $value): ShopShippingProfileInterface
    {
        $this->shippingProfileDestinations = $value;

        return $this;
    }

    public function setShippingProfileId(int $value): ShopShippingProfileInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    /**
     * @param array<int, ShopShippingProfileUpgradeInterface> $value
     */
    public function setShippingProfileUpgrades(array $value): ShopShippingProfileInterface
    {
        $this->shippingProfileUpgrades = $value;

        return $this;
    }

    public function setTitle(?string $value): ShopShippingProfileInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setUserId(?int $value): ShopShippingProfileInterface
    {
        $this->userId = $value;

        return $this;
    }
}
