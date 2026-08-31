<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopShippingProfileRequest implements UpdateShopShippingProfileRequestInterface
{
    private ?int $maxProcessingTime = null;
    private ?int $minProcessingTime = null;
    private ?string $originCountryIso = null;
    private ?string $originPostalCode = null;
    private ?string $processingTimeUnit = null;
    private ?string $title = null;

    public function getMaxProcessingTime(): ?int
    {
        return $this->maxProcessingTime;
    }

    public function getMinProcessingTime(): ?int
    {
        return $this->minProcessingTime;
    }

    public function getOriginCountryIso(): ?string
    {
        return $this->originCountryIso;
    }

    public function getOriginPostalCode(): ?string
    {
        return $this->originPostalCode;
    }

    public function getProcessingTimeUnit(): ?string
    {
        return $this->processingTimeUnit;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setMaxProcessingTime(?int $value): UpdateShopShippingProfileRequestInterface
    {
        $this->maxProcessingTime = $value;

        return $this;
    }

    public function setMinProcessingTime(?int $value): UpdateShopShippingProfileRequestInterface
    {
        $this->minProcessingTime = $value;

        return $this;
    }

    public function setOriginCountryIso(?string $value): UpdateShopShippingProfileRequestInterface
    {
        $this->originCountryIso = $value;

        return $this;
    }

    public function setOriginPostalCode(?string $value): UpdateShopShippingProfileRequestInterface
    {
        $this->originPostalCode = $value;

        return $this;
    }

    public function setProcessingTimeUnit(?string $value): UpdateShopShippingProfileRequestInterface
    {
        $this->processingTimeUnit = $value;

        return $this;
    }

    public function setTitle(?string $value): UpdateShopShippingProfileRequestInterface
    {
        $this->title = $value;

        return $this;
    }
}
