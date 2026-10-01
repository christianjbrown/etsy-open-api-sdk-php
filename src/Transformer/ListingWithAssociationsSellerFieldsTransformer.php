<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_array;

final class ListingWithAssociationsSellerFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    private ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer;
    private ShopShippingProfileTransformerInterface $shopShippingProfileTransformer;
    private ShopTransformerInterface $shopTransformer;
    private UserTransformerInterface $userTransformer;

    public function __construct(ShopProductionPartnersTransformerInterface $shopProductionPartnersTransformer, ShopShippingProfileTransformerInterface $shopShippingProfileTransformer, ShopTransformerInterface $shopTransformer, UserTransformerInterface $userTransformer)
    {
        $this->shopProductionPartnersTransformer = $shopProductionPartnersTransformer;
        $this->shopShippingProfileTransformer = $shopShippingProfileTransformer;
        $this->shopTransformer = $shopTransformer;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        $this->applyShop($listing, $data);
        $this->applyUser($listing, $data);
        $this->applyShippingProfile($listing, $data);
        $this->applyProductionPartners($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyProductionPartners(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_PRODUCTION_PARTNERS])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_PRODUCTION_PARTNERS])) {
            return;
        }
        $listing->setProductionPartners($this->shopProductionPartnersTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_PRODUCTION_PARTNERS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShippingProfile(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE])) {
            return;
        }
        $listing->setShippingProfile($this->shopShippingProfileTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_SHIPPING_PROFILE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShop(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_SHOP])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_SHOP])) {
            return;
        }
        $listing->setShop($this->shopTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_SHOP]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyUser(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_USER])) {
            return;
        }
        if (!is_array($data[ListingWithAssociationsTransformerInterface::KEY_USER])) {
            return;
        }
        $listing->setUser($this->userTransformer->transform($data[ListingWithAssociationsTransformerInterface::KEY_USER]));
    }
}
