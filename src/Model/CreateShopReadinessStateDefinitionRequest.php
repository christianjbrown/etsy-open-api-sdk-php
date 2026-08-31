<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateShopReadinessStateDefinitionRequest implements CreateShopReadinessStateDefinitionRequestInterface
{
    private int $maxProcessingTime;
    private int $minProcessingTime;
    private ?string $processingTimeUnit = null;
    private string $readinessState;

    public function __construct(string $readinessState, int $minProcessingTime, int $maxProcessingTime)
    {
        $this->readinessState = $readinessState;
        $this->minProcessingTime = $minProcessingTime;
        $this->maxProcessingTime = $maxProcessingTime;
    }

    public function getMaxProcessingTime(): int
    {
        return $this->maxProcessingTime;
    }

    public function getMinProcessingTime(): int
    {
        return $this->minProcessingTime;
    }

    public function getProcessingTimeUnit(): ?string
    {
        return $this->processingTimeUnit;
    }

    public function getReadinessState(): string
    {
        return $this->readinessState;
    }

    public function setMaxProcessingTime(int $value): CreateShopReadinessStateDefinitionRequestInterface
    {
        $this->maxProcessingTime = $value;

        return $this;
    }

    public function setMinProcessingTime(int $value): CreateShopReadinessStateDefinitionRequestInterface
    {
        $this->minProcessingTime = $value;

        return $this;
    }

    public function setProcessingTimeUnit(?string $value): CreateShopReadinessStateDefinitionRequestInterface
    {
        $this->processingTimeUnit = $value;

        return $this;
    }

    public function setReadinessState(string $value): CreateShopReadinessStateDefinitionRequestInterface
    {
        $this->readinessState = $value;

        return $this;
    }
}
