<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopReadinessStateDefinition implements ShopReadinessStateDefinitionInterface
{
    private ?int $maxProcessingDays = null;
    private ?int $minProcessingDays = null;
    private ?string $processingDaysDisplayLabel = null;
    private ?string $readinessState = null;
    private int $readinessStateId;
    private ?int $shopId = null;

    public function __construct(int $readinessStateId)
    {
        $this->readinessStateId = $readinessStateId;
    }

    public function getMaxProcessingDays(): ?int
    {
        return $this->maxProcessingDays;
    }

    public function getMinProcessingDays(): ?int
    {
        return $this->minProcessingDays;
    }

    public function getProcessingDaysDisplayLabel(): ?string
    {
        return $this->processingDaysDisplayLabel;
    }

    public function getReadinessState(): ?string
    {
        return $this->readinessState;
    }

    public function getReadinessStateId(): int
    {
        return $this->readinessStateId;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setMaxProcessingDays(?int $value): ShopReadinessStateDefinitionInterface
    {
        $this->maxProcessingDays = $value;

        return $this;
    }

    public function setMinProcessingDays(?int $value): ShopReadinessStateDefinitionInterface
    {
        $this->minProcessingDays = $value;

        return $this;
    }

    public function setProcessingDaysDisplayLabel(?string $value): ShopReadinessStateDefinitionInterface
    {
        $this->processingDaysDisplayLabel = $value;

        return $this;
    }

    public function setReadinessState(?string $value): ShopReadinessStateDefinitionInterface
    {
        $this->readinessState = $value;

        return $this;
    }

    public function setReadinessStateId(int $value): ShopReadinessStateDefinitionInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }

    public function setShopId(?int $value): ShopReadinessStateDefinitionInterface
    {
        $this->shopId = $value;

        return $this;
    }
}
