<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class CreateDraftListingRequest implements CreateDraftListingRequestInterface
{
    private string $description;
    private ?string $ecgtAfterSalesServiceInfo = null;
    private ?string $ecgtGaranBrand = null;
    private ?string $ecgtGaranGuaranteeDetails = null;
    private ?string $ecgtGaranModel = null;
    private ?int $ecgtGaranYears = null;
    private ?string $ecgtOtherCommercialGuaranteeDetails = null;
    private ?string $ecgtSoftwareUpdateDetails = null;

    /**
     * @var array<int, int>
     */
    private array $imageIds = [];
    private ?bool $isCustomizable = null;
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
    private float $price;
    private ?int $processingMax = null;
    private ?int $processingMin = null;

    /**
     * @var array<int, int>
     */
    private array $productionPartnerIds = [];
    private int $quantity;
    private ?int $readinessStateId = null;
    private ?int $returnPolicyId = null;
    private ?int $shippingProfileId = null;
    private ?int $shopSectionId = null;
    private ?bool $shouldAutoRenew = null;

    /**
     * @var array<int, string>
     */
    private array $styles = [];

    /**
     * @var array<int, string>
     */
    private array $tags = [];
    private int $taxonomyId;
    private string $title;
    private ?string $type = null;
    private string $whenMade;
    private string $whoMade;

    public function __construct(int $quantity, string $title, string $description, float $price, string $whoMade, string $whenMade, int $taxonomyId)
    {
        $this->quantity = $quantity;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->whoMade = $whoMade;
        $this->whenMade = $whenMade;
        $this->taxonomyId = $taxonomyId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEcgtAfterSalesServiceInfo(): ?string
    {
        return $this->ecgtAfterSalesServiceInfo;
    }

    public function getEcgtGaranBrand(): ?string
    {
        return $this->ecgtGaranBrand;
    }

    public function getEcgtGaranGuaranteeDetails(): ?string
    {
        return $this->ecgtGaranGuaranteeDetails;
    }

    public function getEcgtGaranModel(): ?string
    {
        return $this->ecgtGaranModel;
    }

    public function getEcgtGaranYears(): ?int
    {
        return $this->ecgtGaranYears;
    }

    public function getEcgtOtherCommercialGuaranteeDetails(): ?string
    {
        return $this->ecgtOtherCommercialGuaranteeDetails;
    }

    public function getEcgtSoftwareUpdateDetails(): ?string
    {
        return $this->ecgtSoftwareUpdateDetails;
    }

    /**
     * @return array<int, int>
     */
    public function getImageIds(): array
    {
        return $this->imageIds;
    }

    public function getIsCustomizable(): ?bool
    {
        return $this->isCustomizable;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getProcessingMax(): ?int
    {
        return $this->processingMax;
    }

    public function getProcessingMin(): ?int
    {
        return $this->processingMin;
    }

    /**
     * @return array<int, int>
     */
    public function getProductionPartnerIds(): array
    {
        return $this->productionPartnerIds;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReadinessStateId(): ?int
    {
        return $this->readinessStateId;
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

    /**
     * @return array<int, string>
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTaxonomyId(): int
    {
        return $this->taxonomyId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getWhenMade(): string
    {
        return $this->whenMade;
    }

    public function getWhoMade(): string
    {
        return $this->whoMade;
    }

    public function setDescription(string $value): CreateDraftListingRequestInterface
    {
        $this->description = $value;

        return $this;
    }

    public function setEcgtAfterSalesServiceInfo(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtAfterSalesServiceInfo = $value;

        return $this;
    }

    public function setEcgtGaranBrand(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtGaranBrand = $value;

        return $this;
    }

    public function setEcgtGaranGuaranteeDetails(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtGaranGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtGaranModel(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtGaranModel = $value;

        return $this;
    }

    public function setEcgtGaranYears(?int $value): CreateDraftListingRequestInterface
    {
        $this->ecgtGaranYears = $value;

        return $this;
    }

    public function setEcgtOtherCommercialGuaranteeDetails(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtOtherCommercialGuaranteeDetails = $value;

        return $this;
    }

    public function setEcgtSoftwareUpdateDetails(?string $value): CreateDraftListingRequestInterface
    {
        $this->ecgtSoftwareUpdateDetails = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setImageIds(array $value): CreateDraftListingRequestInterface
    {
        $this->imageIds = $value;

        return $this;
    }

    public function setIsCustomizable(?bool $value): CreateDraftListingRequestInterface
    {
        $this->isCustomizable = $value;

        return $this;
    }

    public function setIsSupply(?bool $value): CreateDraftListingRequestInterface
    {
        $this->isSupply = $value;

        return $this;
    }

    public function setIsTaxable(?bool $value): CreateDraftListingRequestInterface
    {
        $this->isTaxable = $value;

        return $this;
    }

    public function setItemDimensionsUnit(?string $value): CreateDraftListingRequestInterface
    {
        $this->itemDimensionsUnit = $value;

        return $this;
    }

    public function setItemHeight(?float $value): CreateDraftListingRequestInterface
    {
        $this->itemHeight = $value;

        return $this;
    }

    public function setItemLength(?float $value): CreateDraftListingRequestInterface
    {
        $this->itemLength = $value;

        return $this;
    }

    public function setItemWeight(?float $value): CreateDraftListingRequestInterface
    {
        $this->itemWeight = $value;

        return $this;
    }

    public function setItemWeightUnit(?string $value): CreateDraftListingRequestInterface
    {
        $this->itemWeightUnit = $value;

        return $this;
    }

    public function setItemWidth(?float $value): CreateDraftListingRequestInterface
    {
        $this->itemWidth = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setMaterials(array $value): CreateDraftListingRequestInterface
    {
        $this->materials = $value;

        return $this;
    }

    public function setPrice(float $value): CreateDraftListingRequestInterface
    {
        $this->price = $value;

        return $this;
    }

    public function setProcessingMax(?int $value): CreateDraftListingRequestInterface
    {
        $this->processingMax = $value;

        return $this;
    }

    public function setProcessingMin(?int $value): CreateDraftListingRequestInterface
    {
        $this->processingMin = $value;

        return $this;
    }

    /**
     * @param array<int, int> $value
     */
    public function setProductionPartnerIds(array $value): CreateDraftListingRequestInterface
    {
        $this->productionPartnerIds = $value;

        return $this;
    }

    public function setQuantity(int $value): CreateDraftListingRequestInterface
    {
        $this->quantity = $value;

        return $this;
    }

    public function setReadinessStateId(?int $value): CreateDraftListingRequestInterface
    {
        $this->readinessStateId = $value;

        return $this;
    }

    public function setReturnPolicyId(?int $value): CreateDraftListingRequestInterface
    {
        $this->returnPolicyId = $value;

        return $this;
    }

    public function setShippingProfileId(?int $value): CreateDraftListingRequestInterface
    {
        $this->shippingProfileId = $value;

        return $this;
    }

    public function setShopSectionId(?int $value): CreateDraftListingRequestInterface
    {
        $this->shopSectionId = $value;

        return $this;
    }

    public function setShouldAutoRenew(?bool $value): CreateDraftListingRequestInterface
    {
        $this->shouldAutoRenew = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setStyles(array $value): CreateDraftListingRequestInterface
    {
        $this->styles = $value;

        return $this;
    }

    /**
     * @param array<int, string> $value
     */
    public function setTags(array $value): CreateDraftListingRequestInterface
    {
        $this->tags = $value;

        return $this;
    }

    public function setTaxonomyId(int $value): CreateDraftListingRequestInterface
    {
        $this->taxonomyId = $value;

        return $this;
    }

    public function setTitle(string $value): CreateDraftListingRequestInterface
    {
        $this->title = $value;

        return $this;
    }

    public function setType(?string $value): CreateDraftListingRequestInterface
    {
        $this->type = $value;

        return $this;
    }

    public function setWhenMade(string $value): CreateDraftListingRequestInterface
    {
        $this->whenMade = $value;

        return $this;
    }

    public function setWhoMade(string $value): CreateDraftListingRequestInterface
    {
        $this->whoMade = $value;

        return $this;
    }
}
