<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class SellerTaxonomyNode implements SellerTaxonomyNodeInterface
{
    /**
     * @var array<int, SellerTaxonomyNodeInterface>
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
     * @return array<int, SellerTaxonomyNodeInterface>
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
     * @param array<int, SellerTaxonomyNodeInterface> $value
     */
    public function setChildren(array $value): SellerTaxonomyNodeInterface
    {
        $this->children = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setFullPathTaxonomyIds(array $value): SellerTaxonomyNodeInterface
    {
        $this->fullPathTaxonomyIds = $value;

        return $this;
    }

    public function setId(int $value): SellerTaxonomyNodeInterface
    {
        $this->id = $value;

        return $this;
    }

    public function setLevel(?int $value): SellerTaxonomyNodeInterface
    {
        $this->level = $value;

        return $this;
    }

    public function setName(?string $value): SellerTaxonomyNodeInterface
    {
        $this->name = $value;

        return $this;
    }

    public function setParentId(?int $value): SellerTaxonomyNodeInterface
    {
        $this->parentId = $value;

        return $this;
    }
}
