<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function count;
use function is_array;

final class ListingWithAssociationsCatalogFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    private ListingInventoryTransformerInterface $listingInventoryTransformer;
    private ListingPersonalizationTransformerInterface $listingPersonalizationTransformer;
    private ListingTranslationTransformerInterface $listingTranslationTransformer;

    public function __construct(ListingInventoryTransformerInterface $listingInventoryTransformer, ListingPersonalizationTransformerInterface $listingPersonalizationTransformer, ListingTranslationTransformerInterface $listingTranslationTransformer)
    {
        $this->listingInventoryTransformer = $listingInventoryTransformer;
        $this->listingPersonalizationTransformer = $listingPersonalizationTransformer;
        $this->listingTranslationTransformer = $listingTranslationTransformer;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        $this->applyInventory($listing, $data);
        $this->applyPersonalization($listing, $data);
        $this->applyTranslations($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyInventory(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_INVENTORY])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_INVENTORY])) {
            return;
        }
        $listing->setInventory($this->listingInventoryTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_INVENTORY]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPersonalization(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_PERSONALIZATION])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_PERSONALIZATION])) {
            return;
        }
        $listing->setPersonalization($this->listingPersonalizationTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_PERSONALIZATION]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTranslations(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS])) {
            return;
        }
        $translations = [];
        $languages = array_keys($data[ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS]);
        for ($i = 0, $languageCount = count($languages); $i < $languageCount; ++$i) {
            $translationData = $data[ListingWithAssociationsTransformerInterface::KEY_TRANSLATIONS][$languages[$i]];
            if (!is_array($translationData)) {
                continue;
            }
            $translations[$languages[$i]] = $this->listingTranslationTransformer->transform($translationData);
        }
        $listing->setTranslations($translations);
    }
}
