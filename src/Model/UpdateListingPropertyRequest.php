<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateListingPropertyRequest implements UpdateListingPropertyRequestInterface
{
    private ?int $scaleId = null;

    /**
     * @var array<int, int>
     */
    private array $valueIds = [];

    /**
     * @var array<int, string>
     */
    private array $values = [];

    /**
     * @param array<int, int>    $valueIds
     * @param array<int, string> $values
     */
    public function __construct(array $valueIds, array $values)
    {
        $this->valueIds = $valueIds;
        $this->values = $values;
    }

    public function getScaleId(): ?int
    {
        return $this->scaleId;
    }

    /**
     * @return array<int, int>
     */
    public function getValueIds(): array
    {
        return $this->valueIds;
    }

    /**
     * @return array<int, string>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function setScaleId(?int $value): UpdateListingPropertyRequestInterface
    {
        $this->scaleId = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setValueIds(array $value): UpdateListingPropertyRequestInterface
    {
        $this->valueIds = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setValues(array $value): UpdateListingPropertyRequestInterface
    {
        $this->values = $value;

        return $this;
    }
}
