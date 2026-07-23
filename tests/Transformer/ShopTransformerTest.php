<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Shop;
use ChristianBrown\Etsy\Model\ShopInterface;
use ChristianBrown\Etsy\Transformer\ShopTransformer;
use ChristianBrown\Etsy\Transformer\ShopTransformerInterface;
use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[CoversClass(Shop::class)]
#[CoversClass(ShopTransformer::class)]
final class ShopTransformerTest extends TestCase
{
    public function testSetShopId(): void
    {
        $shop = new Shop(1);

        self::assertSame(2, $shop->setShopId(2)->getShopId());
    }

    public function testTransform(): void
    {
        $data = [
            ShopTransformerInterface::KEY_SHOP_ID => 9000,
            ShopTransformerInterface::KEY_ACCEPTS_CUSTOM_REQUESTS => true,
            ShopTransformerInterface::KEY_ANNOUNCEMENT => 'v_announcement',
            ShopTransformerInterface::KEY_CREATE_DATE => 100,
            ShopTransformerInterface::KEY_CREATED_TIMESTAMP => 101,
            ShopTransformerInterface::KEY_CURRENCY_CODE => 'v_currencyCode',
            ShopTransformerInterface::KEY_DIGITAL_LISTING_COUNT => 102,
            ShopTransformerInterface::KEY_DIGITAL_SALE_MESSAGE => 'v_digitalSaleMessage',
            ShopTransformerInterface::KEY_HAS_ONBOARDED_STRUCTURED_POLICIES => true,
            ShopTransformerInterface::KEY_HAS_UNSTRUCTURED_POLICIES => true,
            ShopTransformerInterface::KEY_ICON_URL_FULLXFULL => 'v_iconUrlFullxfull',
            ShopTransformerInterface::KEY_IMAGE_URL_760X100 => 'v_imageUrl760x100',
            ShopTransformerInterface::KEY_INCLUDE_DISPUTE_FORM_LINK => true,
            ShopTransformerInterface::KEY_IS_CALCULATED_ELIGIBLE => true,
            ShopTransformerInterface::KEY_IS_DIRECT_CHECKOUT_ONBOARDED => true,
            ShopTransformerInterface::KEY_IS_ETSY_PAYMENTS_ONBOARDED => true,
            ShopTransformerInterface::KEY_IS_OPTED_IN_TO_BUYER_PROMISE => true,
            ShopTransformerInterface::KEY_IS_SHOP_US_BASED => true,
            ShopTransformerInterface::KEY_IS_USING_STRUCTURED_POLICIES => true,
            ShopTransformerInterface::KEY_IS_VACATION => true,
            ShopTransformerInterface::KEY_LANGUAGES => ['en', 'fr'],
            ShopTransformerInterface::KEY_LISTING_ACTIVE_COUNT => 103,
            ShopTransformerInterface::KEY_LOGIN_NAME => 'v_loginName',
            ShopTransformerInterface::KEY_NUM_FAVORERS => 104,
            ShopTransformerInterface::KEY_POLICY_ADDITIONAL => 'v_policyAdditional',
            ShopTransformerInterface::KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO => true,
            ShopTransformerInterface::KEY_POLICY_PAYMENT => 'v_policyPayment',
            ShopTransformerInterface::KEY_POLICY_PRIVACY => 'v_policyPrivacy',
            ShopTransformerInterface::KEY_POLICY_REFUNDS => 'v_policyRefunds',
            ShopTransformerInterface::KEY_POLICY_SELLER_INFO => 'v_policySellerInfo',
            ShopTransformerInterface::KEY_POLICY_SHIPPING => 'v_policyShipping',
            ShopTransformerInterface::KEY_POLICY_UPDATE_DATE => 105,
            ShopTransformerInterface::KEY_POLICY_WELCOME => 'v_policyWelcome',
            ShopTransformerInterface::KEY_REVIEW_AVERAGE => 4.5,
            ShopTransformerInterface::KEY_REVIEW_COUNT => 106,
            ShopTransformerInterface::KEY_SALE_MESSAGE => 'v_saleMessage',
            ShopTransformerInterface::KEY_SHIPPING_FROM_COUNTRY_ISO => 'v_shippingFromCountryIso',
            ShopTransformerInterface::KEY_SHOP_LOCATION_COUNTRY_ISO => 'v_shopLocationCountryIso',
            ShopTransformerInterface::KEY_SHOP_NAME => 'v_shopName',
            ShopTransformerInterface::KEY_TITLE => 'v_title',
            ShopTransformerInterface::KEY_TRANSACTION_SOLD_COUNT => 107,
            ShopTransformerInterface::KEY_UPDATE_DATE => 108,
            ShopTransformerInterface::KEY_UPDATED_TIMESTAMP => 109,
            ShopTransformerInterface::KEY_URL => 'v_url',
            ShopTransformerInterface::KEY_USER_ID => 110,
            ShopTransformerInterface::KEY_VACATION_AUTOREPLY => 'v_vacationAutoreply',
            ShopTransformerInterface::KEY_VACATION_MESSAGE => 'v_vacationMessage',
        ];

        $transformer = new ShopTransformer();

        $actual = $transformer->transform($data);

        self::assertSame(9000, $actual->getShopId());
        self::assertTrue($actual->getAcceptsCustomRequests());
        self::assertSame('v_announcement', $actual->getAnnouncement());
        self::assertSame(100, $actual->getCreateDate());
        self::assertSame(101, $actual->getCreatedTimestamp());
        self::assertSame('v_currencyCode', $actual->getCurrencyCode());
        self::assertSame(102, $actual->getDigitalListingCount());
        self::assertSame('v_digitalSaleMessage', $actual->getDigitalSaleMessage());
        self::assertTrue($actual->getHasOnboardedStructuredPolicies());
        self::assertTrue($actual->getHasUnstructuredPolicies());
        self::assertSame('v_iconUrlFullxfull', $actual->getIconUrlFullxfull());
        self::assertSame('v_imageUrl760x100', $actual->getImageUrl760x100());
        self::assertTrue($actual->getIncludeDisputeFormLink());
        self::assertTrue($actual->getIsCalculatedEligible());
        self::assertTrue($actual->getIsDirectCheckoutOnboarded());
        self::assertTrue($actual->getIsEtsyPaymentsOnboarded());
        self::assertTrue($actual->getIsOptedInToBuyerPromise());
        self::assertTrue($actual->getIsShopUsBased());
        self::assertTrue($actual->getIsUsingStructuredPolicies());
        self::assertTrue($actual->getIsVacation());
        self::assertSame(['en', 'fr'], $actual->getLanguages());
        self::assertSame(103, $actual->getListingActiveCount());
        self::assertSame('v_loginName', $actual->getLoginName());
        self::assertSame(104, $actual->getNumFavorers());
        self::assertSame('v_policyAdditional', $actual->getPolicyAdditional());
        self::assertTrue($actual->getPolicyHasPrivateReceiptInfo());
        self::assertSame('v_policyPayment', $actual->getPolicyPayment());
        self::assertSame('v_policyPrivacy', $actual->getPolicyPrivacy());
        self::assertSame('v_policyRefunds', $actual->getPolicyRefunds());
        self::assertSame('v_policySellerInfo', $actual->getPolicySellerInfo());
        self::assertSame('v_policyShipping', $actual->getPolicyShipping());
        self::assertSame(105, $actual->getPolicyUpdateDate());
        self::assertSame('v_policyWelcome', $actual->getPolicyWelcome());
        self::assertSame(4.5, $actual->getReviewAverage());
        self::assertSame(106, $actual->getReviewCount());
        self::assertSame('v_saleMessage', $actual->getSaleMessage());
        self::assertSame('v_shippingFromCountryIso', $actual->getShippingFromCountryIso());
        self::assertSame('v_shopLocationCountryIso', $actual->getShopLocationCountryIso());
        self::assertSame('v_shopName', $actual->getShopName());
        self::assertSame('v_title', $actual->getTitle());
        self::assertSame(107, $actual->getTransactionSoldCount());
        self::assertSame(108, $actual->getUpdateDate());
        self::assertSame(109, $actual->getUpdatedTimestamp());
        self::assertSame('v_url', $actual->getUrl());
        self::assertSame(110, $actual->getUserId());
        self::assertSame('v_vacationAutoreply', $actual->getVacationAutoreply());
        self::assertSame('v_vacationMessage', $actual->getVacationMessage());
    }

    /**
     * @param array<string, mixed>         $data
     * @param Closure(ShopInterface): void $assert
     */
    #[DataProvider('provideTransformOptionalFieldStatesCases')]
    public function testTransformOptionalFieldStates(array $data, Closure $assert): void
    {
        $transformer = new ShopTransformer();

        $assert($transformer->transform($data));
    }

    /**
     * @return iterable<string, array{array<string, mixed>, Closure(ShopInterface): void}>
     */
    public static function provideTransformOptionalFieldStatesCases(): iterable
    {
        $id = ShopTransformerInterface::KEY_SHOP_ID;

        yield 'allOptionalAbsent' => [
            [$id => 1],
            static function (ShopInterface $model): void {
                self::assertNull($model->getAcceptsCustomRequests());
                self::assertNull($model->getAnnouncement());
                self::assertNull($model->getCreateDate());
                self::assertSame([], $model->getLanguages());
                self::assertNull($model->getReviewAverage());
            },
        ];

        yield 'acceptsCustomRequestsWrongType' => [[$id => 1, ShopTransformerInterface::KEY_ACCEPTS_CUSTOM_REQUESTS => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getAcceptsCustomRequests());
        }];
        yield 'announcementWrongType' => [[$id => 1, ShopTransformerInterface::KEY_ANNOUNCEMENT => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getAnnouncement());
        }];
        yield 'createDateWrongType' => [[$id => 1, ShopTransformerInterface::KEY_CREATE_DATE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getCreateDate());
        }];
        yield 'createdTimestampWrongType' => [[$id => 1, ShopTransformerInterface::KEY_CREATED_TIMESTAMP => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getCreatedTimestamp());
        }];
        yield 'currencyCodeWrongType' => [[$id => 1, ShopTransformerInterface::KEY_CURRENCY_CODE => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getCurrencyCode());
        }];
        yield 'digitalListingCountWrongType' => [[$id => 1, ShopTransformerInterface::KEY_DIGITAL_LISTING_COUNT => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getDigitalListingCount());
        }];
        yield 'digitalSaleMessageWrongType' => [[$id => 1, ShopTransformerInterface::KEY_DIGITAL_SALE_MESSAGE => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getDigitalSaleMessage());
        }];
        yield 'hasOnboardedStructuredPoliciesWrongType' => [[$id => 1, ShopTransformerInterface::KEY_HAS_ONBOARDED_STRUCTURED_POLICIES => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getHasOnboardedStructuredPolicies());
        }];
        yield 'hasUnstructuredPoliciesWrongType' => [[$id => 1, ShopTransformerInterface::KEY_HAS_UNSTRUCTURED_POLICIES => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getHasUnstructuredPolicies());
        }];
        yield 'iconUrlFullxfullWrongType' => [[$id => 1, ShopTransformerInterface::KEY_ICON_URL_FULLXFULL => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getIconUrlFullxfull());
        }];
        yield 'imageUrl760x100WrongType' => [[$id => 1, ShopTransformerInterface::KEY_IMAGE_URL_760X100 => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getImageUrl760x100());
        }];
        yield 'includeDisputeFormLinkWrongType' => [[$id => 1, ShopTransformerInterface::KEY_INCLUDE_DISPUTE_FORM_LINK => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIncludeDisputeFormLink());
        }];
        yield 'isCalculatedEligibleWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_CALCULATED_ELIGIBLE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsCalculatedEligible());
        }];
        yield 'isDirectCheckoutOnboardedWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_DIRECT_CHECKOUT_ONBOARDED => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsDirectCheckoutOnboarded());
        }];
        yield 'isEtsyPaymentsOnboardedWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_ETSY_PAYMENTS_ONBOARDED => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsEtsyPaymentsOnboarded());
        }];
        yield 'isOptedInToBuyerPromiseWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_OPTED_IN_TO_BUYER_PROMISE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsOptedInToBuyerPromise());
        }];
        yield 'isShopUsBasedWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_SHOP_US_BASED => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsShopUsBased());
        }];
        yield 'isUsingStructuredPoliciesWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_USING_STRUCTURED_POLICIES => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsUsingStructuredPolicies());
        }];
        yield 'isVacationWrongType' => [[$id => 1, ShopTransformerInterface::KEY_IS_VACATION => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getIsVacation());
        }];
        yield 'languagesNonArray' => [[$id => 1, ShopTransformerInterface::KEY_LANGUAGES => 'x'], static function (ShopInterface $m): void {
            self::assertSame([], $m->getLanguages());
        }];
        yield 'languagesNonStringElement' => [[$id => 1, ShopTransformerInterface::KEY_LANGUAGES => ['en', 42]], static function (ShopInterface $m): void {
            self::assertSame(['en'], $m->getLanguages());
        }];
        yield 'languagesSingleString' => [[$id => 1, ShopTransformerInterface::KEY_LANGUAGES => ['en']], static function (ShopInterface $m): void {
            self::assertSame(['en'], $m->getLanguages());
        }];
        yield 'languagesSingleNonString' => [[$id => 1, ShopTransformerInterface::KEY_LANGUAGES => [42]], static function (ShopInterface $m): void {
            self::assertSame([], $m->getLanguages());
        }];
        yield 'languagesEmpty' => [[$id => 1, ShopTransformerInterface::KEY_LANGUAGES => []], static function (ShopInterface $m): void {
            self::assertSame([], $m->getLanguages());
        }];
        yield 'listingActiveCountWrongType' => [[$id => 1, ShopTransformerInterface::KEY_LISTING_ACTIVE_COUNT => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getListingActiveCount());
        }];
        yield 'loginNameWrongType' => [[$id => 1, ShopTransformerInterface::KEY_LOGIN_NAME => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getLoginName());
        }];
        yield 'numFavorersWrongType' => [[$id => 1, ShopTransformerInterface::KEY_NUM_FAVORERS => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getNumFavorers());
        }];
        yield 'policyAdditionalWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_ADDITIONAL => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyAdditional());
        }];
        yield 'policyHasPrivateReceiptInfoWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_HAS_PRIVATE_RECEIPT_INFO => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyHasPrivateReceiptInfo());
        }];
        yield 'policyPaymentWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_PAYMENT => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyPayment());
        }];
        yield 'policyPrivacyWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_PRIVACY => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyPrivacy());
        }];
        yield 'policyRefundsWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_REFUNDS => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyRefunds());
        }];
        yield 'policySellerInfoWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_SELLER_INFO => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicySellerInfo());
        }];
        yield 'policyShippingWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_SHIPPING => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyShipping());
        }];
        yield 'policyUpdateDateWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_UPDATE_DATE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyUpdateDate());
        }];
        yield 'policyWelcomeWrongType' => [[$id => 1, ShopTransformerInterface::KEY_POLICY_WELCOME => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getPolicyWelcome());
        }];
        yield 'reviewAverageWrongType' => [[$id => 1, ShopTransformerInterface::KEY_REVIEW_AVERAGE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getReviewAverage());
        }];
        yield 'reviewAverageInt' => [[$id => 1, ShopTransformerInterface::KEY_REVIEW_AVERAGE => 5], static function (ShopInterface $m): void {
            self::assertSame(5.0, $m->getReviewAverage());
        }];
        yield 'reviewCountWrongType' => [[$id => 1, ShopTransformerInterface::KEY_REVIEW_COUNT => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getReviewCount());
        }];
        yield 'saleMessageWrongType' => [[$id => 1, ShopTransformerInterface::KEY_SALE_MESSAGE => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getSaleMessage());
        }];
        yield 'shippingFromCountryIsoWrongType' => [[$id => 1, ShopTransformerInterface::KEY_SHIPPING_FROM_COUNTRY_ISO => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getShippingFromCountryIso());
        }];
        yield 'shopLocationCountryIsoWrongType' => [[$id => 1, ShopTransformerInterface::KEY_SHOP_LOCATION_COUNTRY_ISO => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getShopLocationCountryIso());
        }];
        yield 'shopNameWrongType' => [[$id => 1, ShopTransformerInterface::KEY_SHOP_NAME => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getShopName());
        }];
        yield 'titleWrongType' => [[$id => 1, ShopTransformerInterface::KEY_TITLE => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getTitle());
        }];
        yield 'transactionSoldCountWrongType' => [[$id => 1, ShopTransformerInterface::KEY_TRANSACTION_SOLD_COUNT => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getTransactionSoldCount());
        }];
        yield 'updateDateWrongType' => [[$id => 1, ShopTransformerInterface::KEY_UPDATE_DATE => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getUpdateDate());
        }];
        yield 'updatedTimestampWrongType' => [[$id => 1, ShopTransformerInterface::KEY_UPDATED_TIMESTAMP => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getUpdatedTimestamp());
        }];
        yield 'urlWrongType' => [[$id => 1, ShopTransformerInterface::KEY_URL => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getUrl());
        }];
        yield 'userIdWrongType' => [[$id => 1, ShopTransformerInterface::KEY_USER_ID => 'x'], static function (ShopInterface $m): void {
            self::assertNull($m->getUserId());
        }];
        yield 'vacationAutoreplyWrongType' => [[$id => 1, ShopTransformerInterface::KEY_VACATION_AUTOREPLY => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getVacationAutoreply());
        }];
        yield 'vacationMessageWrongType' => [[$id => 1, ShopTransformerInterface::KEY_VACATION_MESSAGE => 42], static function (ShopInterface $m): void {
            self::assertNull($m->getVacationMessage());
        }];
    }

    /**
     * @param mixed[] $data
     */
    #[TestWith([[]])]
    #[TestWith([[ShopTransformerInterface::KEY_SHOP_ID => 'not-int']])]
    public function testTransformThrowsOnInvalidShopId(array $data): void
    {
        $transformer = new ShopTransformer();

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage(sprintf(ShopTransformerInterface::UNEXPECTED_INTEGER_SPRINTF, ShopTransformerInterface::KEY_SHOP_ID));

        $transformer->transform($data);
    }
}
