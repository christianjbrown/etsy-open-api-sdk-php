<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Shop;
use ChristianBrown\Etsy\Model\ShopInterface;

use function array_values;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final class ShopTransformer implements ShopTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopInterface
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHOP_ID));
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_SHOP_ID));
        }
        $shop = new Shop($data[self::KEY_SHOP_ID]);

        self::applyAcceptsCustomRequests($shop, $data);
        self::applyAnnouncement($shop, $data);
        self::applyCreateDate($shop, $data);
        self::applyCreatedTimestamp($shop, $data);
        self::applyCurrencyCode($shop, $data);
        self::applyDigitalListingCount($shop, $data);
        self::applyDigitalSaleMessage($shop, $data);
        self::applyHasOnboardedStructuredPolicies($shop, $data);
        self::applyHasUnstructuredPolicies($shop, $data);
        self::applyIconUrlFullxfull($shop, $data);
        self::applyImageUrl760x100($shop, $data);
        self::applyIncludeDisputeFormLink($shop, $data);
        self::applyIsCalculatedEligible($shop, $data);
        self::applyIsDirectCheckoutOnboarded($shop, $data);
        self::applyIsEtsyPaymentsOnboarded($shop, $data);
        self::applyIsOptedInToBuyerPromise($shop, $data);
        self::applyIsShopUsBased($shop, $data);
        self::applyIsUsingStructuredPolicies($shop, $data);
        self::applyIsVacation($shop, $data);
        self::applyLanguages($shop, $data);
        self::applyListingActiveCount($shop, $data);
        self::applyLoginName($shop, $data);
        self::applyNumFavorers($shop, $data);
        self::applyPolicyAdditional($shop, $data);
        self::applyPolicyHasPrivateReceiptInfo($shop, $data);
        self::applyPolicyPayment($shop, $data);
        self::applyPolicyPrivacy($shop, $data);
        self::applyPolicyRefunds($shop, $data);
        self::applyPolicySellerInfo($shop, $data);
        self::applyPolicyShipping($shop, $data);
        self::applyPolicyUpdateDate($shop, $data);
        self::applyPolicyWelcome($shop, $data);
        self::applyReviewAverage($shop, $data);
        self::applyReviewCount($shop, $data);
        self::applySaleMessage($shop, $data);
        self::applyShippingFromCountryIso($shop, $data);
        self::applyShopLocationCountryIso($shop, $data);
        self::applyShopName($shop, $data);
        self::applyTitle($shop, $data);
        self::applyTransactionSoldCount($shop, $data);
        self::applyUpdateDate($shop, $data);
        self::applyUpdatedTimestamp($shop, $data);
        self::applyUrl($shop, $data);
        self::applyUserId($shop, $data);
        self::applyVacationAutoreply($shop, $data);
        self::applyVacationMessage($shop, $data);

        return $shop;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAcceptsCustomRequests(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_ACCEPTS_CUSTOM_REQUESTS])) {
            return;
        }
        if (!is_bool($data[self::KEY_ACCEPTS_CUSTOM_REQUESTS])) {
            return;
        }
        $shop->setAcceptsCustomRequests($data[self::KEY_ACCEPTS_CUSTOM_REQUESTS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyAnnouncement(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_ANNOUNCEMENT])) {
            return;
        }
        if (!is_string($data[self::KEY_ANNOUNCEMENT])) {
            return;
        }
        $shop->setAnnouncement($data[self::KEY_ANNOUNCEMENT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateDate(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_DATE])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_DATE])) {
            return;
        }
        $shop->setCreateDate($data[self::KEY_CREATE_DATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $shop->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCurrencyCode(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_CURRENCY_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_CURRENCY_CODE])) {
            return;
        }
        $shop->setCurrencyCode($data[self::KEY_CURRENCY_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDigitalListingCount(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_DIGITAL_LISTING_COUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_DIGITAL_LISTING_COUNT])) {
            return;
        }
        $shop->setDigitalListingCount($data[self::KEY_DIGITAL_LISTING_COUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDigitalSaleMessage(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_DIGITAL_SALE_MESSAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_DIGITAL_SALE_MESSAGE])) {
            return;
        }
        $shop->setDigitalSaleMessage($data[self::KEY_DIGITAL_SALE_MESSAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHasOnboardedStructuredPolicies(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_HAS_ONBOARDED_STRUCTURED_POLICIES])) {
            return;
        }
        if (!is_bool($data[self::KEY_HAS_ONBOARDED_STRUCTURED_POLICIES])) {
            return;
        }
        $shop->setHasOnboardedStructuredPolicies($data[self::KEY_HAS_ONBOARDED_STRUCTURED_POLICIES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyHasUnstructuredPolicies(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_HAS_UNSTRUCTURED_POLICIES])) {
            return;
        }
        if (!is_bool($data[self::KEY_HAS_UNSTRUCTURED_POLICIES])) {
            return;
        }
        $shop->setHasUnstructuredPolicies($data[self::KEY_HAS_UNSTRUCTURED_POLICIES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIconUrlFullxfull(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_ICON_URL_FULLXFULL])) {
            return;
        }
        if (!is_string($data[self::KEY_ICON_URL_FULLXFULL])) {
            return;
        }
        $shop->setIconUrlFullxfull($data[self::KEY_ICON_URL_FULLXFULL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyImageUrl760x100(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_IMAGE_URL_760X100])) {
            return;
        }
        if (!is_string($data[self::KEY_IMAGE_URL_760X100])) {
            return;
        }
        $shop->setImageUrl760x100($data[self::KEY_IMAGE_URL_760X100]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIncludeDisputeFormLink(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_INCLUDE_DISPUTE_FORM_LINK])) {
            return;
        }
        if (!is_bool($data[self::KEY_INCLUDE_DISPUTE_FORM_LINK])) {
            return;
        }
        $shop->setIncludeDisputeFormLink($data[self::KEY_INCLUDE_DISPUTE_FORM_LINK]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsCalculatedEligible(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_CALCULATED_ELIGIBLE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_CALCULATED_ELIGIBLE])) {
            return;
        }
        $shop->setIsCalculatedEligible($data[self::KEY_IS_CALCULATED_ELIGIBLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDirectCheckoutOnboarded(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_DIRECT_CHECKOUT_ONBOARDED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DIRECT_CHECKOUT_ONBOARDED])) {
            return;
        }
        $shop->setIsDirectCheckoutOnboarded($data[self::KEY_IS_DIRECT_CHECKOUT_ONBOARDED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsEtsyPaymentsOnboarded(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_ETSY_PAYMENTS_ONBOARDED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_ETSY_PAYMENTS_ONBOARDED])) {
            return;
        }
        $shop->setIsEtsyPaymentsOnboarded($data[self::KEY_IS_ETSY_PAYMENTS_ONBOARDED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsOptedInToBuyerPromise(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_OPTED_IN_TO_BUYER_PROMISE])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_OPTED_IN_TO_BUYER_PROMISE])) {
            return;
        }
        $shop->setIsOptedInToBuyerPromise($data[self::KEY_IS_OPTED_IN_TO_BUYER_PROMISE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsShopUsBased(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_SHOP_US_BASED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_SHOP_US_BASED])) {
            return;
        }
        $shop->setIsShopUsBased($data[self::KEY_IS_SHOP_US_BASED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsUsingStructuredPolicies(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_USING_STRUCTURED_POLICIES])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_USING_STRUCTURED_POLICIES])) {
            return;
        }
        $shop->setIsUsingStructuredPolicies($data[self::KEY_IS_USING_STRUCTURED_POLICIES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsVacation(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_IS_VACATION])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_VACATION])) {
            return;
        }
        $shop->setIsVacation($data[self::KEY_IS_VACATION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLanguages(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_LANGUAGES])) {
            return;
        }
        if (!is_array($data[self::KEY_LANGUAGES])) {
            return;
        }
        $values = [];
        $items = array_values($data[self::KEY_LANGUAGES]);
        for ($i = 0, $itemCount = count($items); $i < $itemCount; ++$i) {
            if (!is_string($items[$i])) {
                continue;
            }
            $values[] = $items[$i];
        }
        $shop->setLanguages($values);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingActiveCount(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_ACTIVE_COUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_ACTIVE_COUNT])) {
            return;
        }
        $shop->setListingActiveCount($data[self::KEY_LISTING_ACTIVE_COUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLoginName(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_LOGIN_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_LOGIN_NAME])) {
            return;
        }
        $shop->setLoginName($data[self::KEY_LOGIN_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNumFavorers(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_NUM_FAVORERS])) {
            return;
        }
        if (!is_int($data[self::KEY_NUM_FAVORERS])) {
            return;
        }
        $shop->setNumFavorers($data[self::KEY_NUM_FAVORERS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyAdditional(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_ADDITIONAL])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_ADDITIONAL])) {
            return;
        }
        $shop->setPolicyAdditional($data[self::KEY_POLICY_ADDITIONAL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyHasPrivateReceiptInfo(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO])) {
            return;
        }
        if (!is_bool($data[self::KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO])) {
            return;
        }
        $shop->setPolicyHasPrivateReceiptInfo($data[self::KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyPayment(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_PAYMENT])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_PAYMENT])) {
            return;
        }
        $shop->setPolicyPayment($data[self::KEY_POLICY_PAYMENT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyPrivacy(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_PRIVACY])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_PRIVACY])) {
            return;
        }
        $shop->setPolicyPrivacy($data[self::KEY_POLICY_PRIVACY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyRefunds(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_REFUNDS])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_REFUNDS])) {
            return;
        }
        $shop->setPolicyRefunds($data[self::KEY_POLICY_REFUNDS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicySellerInfo(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_SELLER_INFO])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_SELLER_INFO])) {
            return;
        }
        $shop->setPolicySellerInfo($data[self::KEY_POLICY_SELLER_INFO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyShipping(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_SHIPPING])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_SHIPPING])) {
            return;
        }
        $shop->setPolicyShipping($data[self::KEY_POLICY_SHIPPING]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyUpdateDate(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_POLICY_UPDATE_DATE])) {
            return;
        }
        if (!is_int($data[self::KEY_POLICY_UPDATE_DATE])) {
            return;
        }
        $shop->setPolicyUpdateDate($data[self::KEY_POLICY_UPDATE_DATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPolicyWelcome(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_POLICY_WELCOME])) {
            return;
        }
        if (!is_string($data[self::KEY_POLICY_WELCOME])) {
            return;
        }
        $shop->setPolicyWelcome($data[self::KEY_POLICY_WELCOME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReviewAverage(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_REVIEW_AVERAGE])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_REVIEW_AVERAGE]);
        if (null === $value) {
            return;
        }
        $shop->setReviewAverage($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReviewCount(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_REVIEW_COUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_REVIEW_COUNT])) {
            return;
        }
        $shop->setReviewCount($data[self::KEY_REVIEW_COUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySaleMessage(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_SALE_MESSAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_SALE_MESSAGE])) {
            return;
        }
        $shop->setSaleMessage($data[self::KEY_SALE_MESSAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingFromCountryIso(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_FROM_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_SHIPPING_FROM_COUNTRY_ISO])) {
            return;
        }
        $shop->setShippingFromCountryIso($data[self::KEY_SHIPPING_FROM_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopLocationCountryIso(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_SHOP_LOCATION_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_SHOP_LOCATION_COUNTRY_ISO])) {
            return;
        }
        $shop->setShopLocationCountryIso($data[self::KEY_SHOP_LOCATION_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopName(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_SHOP_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_SHOP_NAME])) {
            return;
        }
        $shop->setShopName($data[self::KEY_SHOP_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $shop->setTitle($data[self::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTransactionSoldCount(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_TRANSACTION_SOLD_COUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_TRANSACTION_SOLD_COUNT])) {
            return;
        }
        $shop->setTransactionSoldCount($data[self::KEY_TRANSACTION_SOLD_COUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdateDate(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_UPDATE_DATE])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATE_DATE])) {
            return;
        }
        $shop->setUpdateDate($data[self::KEY_UPDATE_DATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $shop->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUrl(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_URL])) {
            return;
        }
        if (!is_string($data[self::KEY_URL])) {
            return;
        }
        $shop->setUrl($data[self::KEY_URL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(Shop $shop, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $shop->setUserId($data[self::KEY_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyVacationAutoreply(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_VACATION_AUTOREPLY])) {
            return;
        }
        if (!is_string($data[self::KEY_VACATION_AUTOREPLY])) {
            return;
        }
        $shop->setVacationAutoreply($data[self::KEY_VACATION_AUTOREPLY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyVacationMessage(Shop $shop, array $data): void
    {
        if (empty($data[self::KEY_VACATION_MESSAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_VACATION_MESSAGE])) {
            return;
        }
        $shop->setVacationMessage($data[self::KEY_VACATION_MESSAGE]);
    }

    private static function toFloat(mixed $value): ?float
    {
        if (is_int($value)) {
            return (float) $value;
        }
        if (is_float($value)) {
            return $value;
        }

        return null;
    }
}
