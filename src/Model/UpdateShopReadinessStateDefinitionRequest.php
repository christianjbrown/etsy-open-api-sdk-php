<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateShopReadinessStateDefinitionRequest implements UpdateShopReadinessStateDefinitionRequestInterface
{
    private ?int $maxProcessingTime = null;
    private ?int $minProcessingTime = null;
    private ?string $processingTimeUnit = null;
    private ?string $readinessState = null;

    public function getMaxProcessingTime(): ?int
    {
        return $this->maxProcessingTime;
    }

    public function getMinProcessingTime(): ?int
    {
        return $this->minProcessingTime;
    }

    public function getProcessingTimeUnit(): ?string
    {
        return $this->processingTimeUnit;
    }

    public function getReadinessState(): ?string
    {
        return $this->readinessState;
    }

    public function setMaxProcessingTime(?int $value): UpdateShopReadinessStateDefinitionRequestInterface
    {
        $this->maxProcessingTime = $value;

        return $this;
    }

    public function setMinProcessingTime(?int $value): UpdateShopReadinessStateDefinitionRequestInterface
    {
        $this->minProcessingTime = $value;

        return $this;
    }

    public function setProcessingTimeUnit(?string $value): UpdateShopReadinessStateDefinitionRequestInterface
    {
        $this->processingTimeUnit = $value;

        return $this;
    }

    public function setReadinessState(?string $value): UpdateShopReadinessStateDefinitionRequestInterface
    {
        $this->readinessState = $value;

        return $this;
    }
}
