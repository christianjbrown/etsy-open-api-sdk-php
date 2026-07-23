<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopReadinessStateDefinitionInterface
{
    public function getMaxProcessingDays(): ?int;

    public function getMinProcessingDays(): ?int;

    public function getProcessingDaysDisplayLabel(): ?string;

    public function getReadinessState(): ?string;

    public function getReadinessStateId(): int;

    public function getShopId(): ?int;

    public function setMaxProcessingDays(?int $value): self;

    public function setMinProcessingDays(?int $value): self;

    public function setProcessingDaysDisplayLabel(?string $value): self;

    public function setReadinessState(?string $value): self;

    public function setReadinessStateId(int $value): self;

    public function setShopId(?int $value): self;
}
