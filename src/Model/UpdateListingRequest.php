<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class UpdateListingRequest implements UpdateListingRequestInterface
{
    private ?string $description = null;
    private ?int $featuredRank = null;

    /**
     * @var array<int, int>
     */
    private array $imageIds = [];
    private ?bool $isSupply = null;
    private ?bool $isTaxable = null;
    private ?string $itemDimensionsUnit = null;
    private ?float $itemHeight = null;
    private ?float $itemLength = null;
    private ?float $itemWeight = null;
    private ?string $itemWeightUnit = null;
    private ?float $itemWidth = null;

    /**
     * @var array<int, string>
     */
    private array $materials = [];

    /**
     * @var array<int, int>
     */
    private array $productionPartnerIds = [];
    private ?int $returnPolicyId = null;
    private ?int $shippingProfileId = null;
    private ?int $shopSectionId = null;
    private ?bool $shouldAutoRenew = null;
    private ?string $state = null;

    /**
     * @var array<int, string>
     */
    private array $tags = [];
    private ?int $taxonomyId = null;
    private ?string $title = null;
    private ?string $type = null;
    private ?string $whenMade = null;
    private ?string $whoMade = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFeaturedRank(): ?int
    {
        return $this->featuredRank;
    }

    /**
     * @return array<int, int>
     */
    public function getImageIds(): array
    {
        return $this->imageIds;
    }

    public function getIsSupply(): ?bool
    {
        return $this->isSupply;
    }

    public function getIsTaxable(): ?bool
    {
        return $this->isTaxable;
    }

    public function getItemDimensionsUnit(): ?string
    {
        return $this->itemDimensionsUnit;
    }

    public function getItemHeight(): ?float
    {
        return $this->itemHeight;
    }

    public function getItemLength(): ?float
    {
        return $this->itemLength;
    }

    public function getItemWeight(): ?float
    {
        return $this->itemWeight;
    }

    public function getItemWeightUnit(): ?string
    {
        return $this->itemWeightUnit;
    }

    public function getItemWidth(): ?float
    {
        return $this->itemWidth;
    }

    /**
     * @return array<int, string>
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    /**
     * @return array<int, int>
     */
    public function getProductionPartnerIds(): array
    {
        return $this->productionPartnerIds;
    }

    public function getReturnPolicyId(): ?int
    {
        return $this->returnPolicyId;
    }

    public function getShippingProfileId(): ?int
    {
        return $this->shippingProfileId;
    }

    public function getShopSectionId(): ?int
    {
        return $this->shopSectionId;
    }

    public function getShouldAutoRenew(): ?bool
    {
        return $this->shouldAutoRenew;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTaxonomyId(): ?int
    {
        return $this->taxonomyId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getWhenMade(): ?string
    {
        return $this->whenMade;
    }

    public function getWhoMade(): ?string
    {
        return $this->whoMade;
    }

    public function setDescription(?string $value): UpdateListingRequestInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setFeaturedRank(?int $value): UpdateListingRequestInterface
    {
        $this->featuredRank = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setImageIds(array $value): UpdateListingRequestInterface
    {
        $this->imageIds = $value;

        return $this;
    }

    public function setIsSupply(?bool $value): UpdateListingRequestInterface
    {
        $this->isSupply = $value;

        return $this;
    }

    public function setIsTaxable(?bool $value): UpdateListingRequestInterface
    {
        $this->isTaxable = $value;

        return $this;
    }

    public function setItemDimensionsUnit(?string $value): UpdateListingRequestInterface
    {
        $this->itemDimensionsUnit = $value;

        return $this;
    }

    public function setItemHeight(?float $value): UpdateListingRequestInterface
    {
        $this->itemHeight = $value;

        return $this;
    }

    public function setItemLength(?float $value): UpdateListingRequestInterface
    {
        $this->itemLength = $value;

        return $this;
    }

    public function setItemWeight(?float $value): UpdateListingRequestInterface
    {
        $this->itemWeight = $value;

        return $this;
    }

    public function setItemWeightUnit(?string $value): UpdateListingRequestInterface
    {
        $this->itemWeightUnit = $value;

        return $this;
    }

    public function setItemWidth(?float $value): UpdateListingRequestInterface
    {
        $this->itemWidth = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): UpdateListingRequestInterface
    {
        $this->materials = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setProductionPartnerIds(array $value): UpdateListingRequestInterface
    {
        $this->productionPartnerIds = $value;

        return $this;
    }

    public function setReturnPolicyId(?int $value): UpdateListingRequestInterface
    {
        $this->returnPolicyId = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): UpdateListingRequestInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setShopSectionId(?int $value): UpdateListingRequestInterface
    {
        $this->shopSectionId = $value;

        return $this;
    }

    public function setShouldAutoRenew(?bool $value): UpdateListingRequestInterface
    {
        $this->shouldAutoRenew = $value;

        return $this;
    }

    public function setState(?string $value): UpdateListingRequestInterface
    {
        $this->state = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): UpdateListingRequestInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTaxonomyId(?int $value): UpdateListingRequestInterface
    {
        $this->taxonomyId = $value;

        return $this;
    }

    public function setTitle(?string $value): UpdateListingRequestInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setType(?string $value): UpdateListingRequestInterface
    {
        $this->type = $value;

        return $this;
    }

    public function setWhenMade(?string $value): UpdateListingRequestInterface
    {
        $this->whenMade = $value;

        return $this;
    }

    public function setWhoMade(?string $value): UpdateListingRequestInterface
    {
        $this->whoMade = $value;

        return $this;
    }
}
