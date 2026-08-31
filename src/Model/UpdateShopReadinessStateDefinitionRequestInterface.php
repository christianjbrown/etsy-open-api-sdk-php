<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateShopReadinessStateDefinition` call.
 */
interface UpdateShopReadinessStateDefinitionRequestInterface
{
    public function getMaxProcessingTime(): ?int;

    public function getMinProcessingTime(): ?int;

    public function getProcessingTimeUnit(): ?string;

    public function getReadinessState(): ?string;

    public function setMaxProcessingTime(?int $value): self;

    public function setMinProcessingTime(?int $value): self;

    public function setProcessingTimeUnit(?string $value): self;

    public function setReadinessState(?string $value): self;
}
