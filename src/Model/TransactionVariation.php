<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class TransactionVariation implements TransactionVariationInterface
{
    private ?string $formattedName = null;
    private ?string $formattedValue = null;
    private ?int $propertyId = null;
    private ?int $questionId = null;
    private ?int $valueId = null;

    public function getFormattedName(): ?string
    {
        return $this->formattedName;
    }

    public function getFormattedValue(): ?string
    {
        return $this->formattedValue;
    }

    public function getPropertyId(): ?int
    {
        return $this->propertyId;
    }

    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    public function setFormattedName(?string $value): TransactionVariationInterface
    {
        $this->formattedName = $value;

        return $this;
    }

    public function setFormattedValue(?string $value): TransactionVariationInterface
    {
        $this->formattedValue = $value;

        return $this;
    }

    public function setPropertyId(?int $value): TransactionVariationInterface
    {
        $this->propertyId = $value;

        return $this;
    }

    public function setQuestionId(?int $value): TransactionVariationInterface
    {
        $this->questionId = $value;

        return $this;
    }

    public function setValueId(?int $value): TransactionVariationInterface
    {
        $this->valueId = $value;

        return $this;
    }
}
