<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of an `updateListingProperty` call.
 */
interface UpdateListingPropertyRequestInterface
{
    public function getScaleId(): ?int;

    /**
     * @return array<int, int>
     */
    public function getValueIds(): array;

    /**
     * @return array<int, string>
     */
    public function getValues(): array;

    public function setScaleId(?int $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setValueIds(array $value): self;

    /**
     * @param array<int, string> $value
     */
    public function setValues(array $value): self;
}
