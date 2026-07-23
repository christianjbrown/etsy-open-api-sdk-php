<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class BuyerTaxonomyNode implements BuyerTaxonomyNodeInterface
{
    /**
     * @var array<int, BuyerTaxonomyNodeInterface>
     */
    private array $children = [];

    /**
     * @var array<int, int>
     */
    private array $fullPathTaxonomyIds = [];
    private int $id;
    private ?int $level = null;
    private ?string $name = null;
    private ?int $parentId = null;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array<int, BuyerTaxonomyNodeInterface>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return array<int, int>
     */
    public function getFullPathTaxonomyIds(): array
    {
        return $this->fullPathTaxonomyIds;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @param array<int, BuyerTaxonomyNodeInterface> $value
     */
    public function setChildren(array $value): BuyerTaxonomyNodeInterface
    {
        $this->children = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setFullPathTaxonomyIds(array $value): BuyerTaxonomyNodeInterface
    {
        $this->fullPathTaxonomyIds = $value;

        return $this;
    }

    public function setId(int $value): BuyerTaxonomyNodeInterface
    {
        $this->id = $value;

        return $this;
    }

    public function setLevel(?int $value): BuyerTaxonomyNodeInterface
    {
        $this->level = $value;

        return $this;
    }

    public function setName(?string $value): BuyerTaxonomyNodeInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setParentId(?int $value): BuyerTaxonomyNodeInterface
    {
        $this->parentId = $value;

        return $this;
    }
}
