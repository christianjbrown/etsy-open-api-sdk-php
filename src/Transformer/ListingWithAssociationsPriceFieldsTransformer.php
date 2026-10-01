<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_array;

final class ListingWithAssociationsPriceFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    private ListingBuyerPriceTransformerInterface $listingBuyerPriceTransformer;
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(ListingBuyerPriceTransformerInterface $listingBuyerPriceTransformer, MoneyTransformerInterface $moneyTransformer)
    {
        $this->listingBuyerPriceTransformer = $listingBuyerPriceTransformer;
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        $this->applyBuyerPrice($listing, $data);
        $this->applyConvertedPrice($listing, $data);
        $this->applyPrice($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyBuyerPrice(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_BUYER_PRICE])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_BUYER_PRICE])) {
            return;
        }
        $listing->setBuyerPrice($this->listingBuyerPriceTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_BUYER_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyConvertedPrice(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_CONVERTED_PRICE])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_CONVERTED_PRICE])) {
            return;
        }
        $listing->setConvertedPrice($this->moneyTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_CONVERTED_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_PRICE])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_PRICE])) {
            return;
        }
        $listing->setPrice($this->moneyTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_PRICE]));
    }
}
