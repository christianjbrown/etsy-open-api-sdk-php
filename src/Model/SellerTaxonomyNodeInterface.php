<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface SellerTaxonomyNodeInterface
{
    /**
     * @return array<int, SellerTaxonomyNodeInterface>
     */
    public function getChildren(): array;

    /**
     * @return array<int, int>
     */
    public function getFullPathTaxonomyIds(): array;

    public function getId(): int;

    public function getLevel(): ?int;

    public function getName(): ?string;

    public function getParentId(): ?int;

    /**
     * @param array<int, SellerTaxonomyNodeInterface> $value
     */
    public function setChildren(array $value): self;

    /**
     * @param array<int, int> $value
     */
    public function setFullPathTaxonomyIds(array $value): self;

    public function setId(int $value): self;

    public function setLevel(?int $value): self;

    public function setName(?string $value): self;

    public function setParentId(?int $value): self;
}
