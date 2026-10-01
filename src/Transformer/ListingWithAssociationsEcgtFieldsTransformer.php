<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function is_bool;
use function is_int;
use function is_string;

final class ListingWithAssociationsEcgtFieldsTransformer implements ListingWithAssociationsFieldsTransformerInterface
{
    /**
     * @phpstan-param mixed[] $data
     */
    public function apply(ListingWithAssociationsInterface $listing, array $data): void
    {
        self::applyEcgtAfterSalesServiceInfo($listing, $data);
        self::applyEcgtCommercialGuaranteeEnabled($listing, $data);
        self::applyEcgtGaranBrand($listing, $data);
        self::applyEcgtGaranGuaranteeDetails($listing, $data);
        self::applyEcgtGaranModel($listing, $data);
        self::applyEcgtGaranYears($listing, $data);
        self::applyEcgtOtherCommercialGuaranteeDetails($listing, $data);
        self::applyEcgtSoftwareUpdateDetails($listing, $data);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtAfterSalesServiceInfo(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_AFTER_SALES_SERVICE_INFO])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_AFTER_SALES_SERVICE_INFO])) {
            return;
        }
        $listing->setEcgtAfterSalesServiceInfo($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_AFTER_SALES_SERVICE_INFO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtCommercialGuaranteeEnabled(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED])) {
            return;
        }
        if (!is_bool($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED])) {
            return;
        }
        $listing->setEcgtCommercialGuaranteeEnabled($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_COMMERCIAL_GUARANTEE_ENABLED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranBrand(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_BRAND])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_BRAND])) {
            return;
        }
        $listing->setEcgtGaranBrand($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_BRAND]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranGuaranteeDetails(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_GUARANTEE_DETAILS])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_GUARANTEE_DETAILS])) {
            return;
        }
        $listing->setEcgtGaranGuaranteeDetails($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_GUARANTEE_DETAILS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranModel(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_MODEL])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_MODEL])) {
            return;
        }
        $listing->setEcgtGaranModel($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_MODEL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtGaranYears(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (!isset($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_YEARS])) {
            return;
        }
        if (!is_int($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_YEARS])) {
            return;
        }
        $listing->setEcgtGaranYears($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_GARAN_YEARS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtOtherCommercialGuaranteeDetails(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS])) {
            return;
        }
        $listing->setEcgtOtherCommercialGuaranteeDetails($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_OTHER_COMMERCIAL_GUARANTEE_DETAILS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyEcgtSoftwareUpdateDetails(ListingWithAssociationsInterface $listing, array $data): void
    {
        if (empty($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_SOFTWARE_UPDATE_DETAILS])) {
            return;
        }
        if (!is_string($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_SOFTWARE_UPDATE_DETAILS])) {
            return;
        }
        $listing->setEcgtSoftwareUpdateDetails($data[ListingWithAssociationsTransformerInterface::KEY_ECGT_SOFTWARE_UPDATE_DETAILS]);
    }
}
