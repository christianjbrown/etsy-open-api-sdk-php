<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Transaction;
use ChristianBrown\Etsy\Model\TransactionInterface;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

final class TransactionTransformer implements TransactionTransformerInterface
{
    private ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer;
    private MoneyTransformerInterface $moneyTransformer;
    private TransactionVariationsTransformerInterface $transactionVariationsTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer, TransactionVariationsTransformerInterface $transactionVariationsTransformer, ListingPropertyValuesTransformerInterface $listingPropertyValuesTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
        $this->transactionVariationsTransformer = $transactionVariationsTransformer;
        $this->listingPropertyValuesTransformer = $listingPropertyValuesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TransactionInterface
    {
        if (!isset($data[self::KEY_TRANSACTION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_TRANSACTION_ID));
        }
        if (!is_int($data[self::KEY_TRANSACTION_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_TRANSACTION_ID));
        }
        $transaction = new Transaction($data[self::KEY_TRANSACTION_ID]);

        self::applyBuyerCoupon($transaction, $data);
        self::applyBuyerUserId($transaction, $data);
        self::applyCreateTimestamp($transaction, $data);
        self::applyCreatedTimestamp($transaction, $data);
        self::applyDescription($transaction, $data);
        self::applyExpectedShipDate($transaction, $data);
        self::applyFileData($transaction, $data);
        self::applyIsDigital($transaction, $data);
        self::applyListingId($transaction, $data);
        self::applyListingImageId($transaction, $data);
        self::applyMaxProcessingDays($transaction, $data);
        self::applyMinProcessingDays($transaction, $data);
        self::applyPaidTimestamp($transaction, $data);
        self::applyProductId($transaction, $data);
        self::applyQuantity($transaction, $data);
        self::applyReceiptId($transaction, $data);
        self::applySellerUserId($transaction, $data);
        self::applyShippedTimestamp($transaction, $data);
        self::applyShippingMethod($transaction, $data);
        self::applyShippingProfileId($transaction, $data);
        self::applyShippingUpgrade($transaction, $data);
        self::applyShopCoupon($transaction, $data);
        self::applySku($transaction, $data);
        self::applyTitle($transaction, $data);
        self::applyTransactionType($transaction, $data);
        $this->applyPrice($transaction, $data);
        $this->applyShippingCost($transaction, $data);
        $this->applyVariations($transaction, $data);
        $this->applyProductData($transaction, $data);

        return $transaction;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerCoupon(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_COUPON])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_BUYER_COUPON]);
        if (null === $value) {
            return;
        }
        $transaction->setBuyerCoupon($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerUserId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        $transaction->setBuyerUserId($data[self::KEY_BUYER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $transaction->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $transaction->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDescription(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_DESCRIPTION])) {
            return;
        }
        if (!is_string($data[self::KEY_DESCRIPTION])) {
            return;
        }
        $transaction->setDescription($data[self::KEY_DESCRIPTION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyExpectedShipDate(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_EXPECTED_SHIP_DATE])) {
            return;
        }
        if (!is_int($data[self::KEY_EXPECTED_SHIP_DATE])) {
            return;
        }
        $transaction->setExpectedShipDate($data[self::KEY_EXPECTED_SHIP_DATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFileData(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_FILE_DATA])) {
            return;
        }
        if (!is_string($data[self::KEY_FILE_DATA])) {
            return;
        }
        $transaction->setFileData($data[self::KEY_FILE_DATA]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDigital(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_IS_DIGITAL])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DIGITAL])) {
            return;
        }
        $transaction->setIsDigital($data[self::KEY_IS_DIGITAL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            return;
        }
        $transaction->setListingId($data[self::KEY_LISTING_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyListingImageId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_LISTING_IMAGE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_LISTING_IMAGE_ID])) {
            return;
        }
        $transaction->setListingImageId($data[self::KEY_LISTING_IMAGE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxProcessingDays(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_MAX_PROCESSING_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_PROCESSING_DAYS])) {
            return;
        }
        $transaction->setMaxProcessingDays($data[self::KEY_MAX_PROCESSING_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMinProcessingDays(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_MIN_PROCESSING_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MIN_PROCESSING_DAYS])) {
            return;
        }
        $transaction->setMinProcessingDays($data[self::KEY_MIN_PROCESSING_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaidTimestamp(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_PAID_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_PAID_TIMESTAMP])) {
            return;
        }
        $transaction->setPaidTimestamp($data[self::KEY_PAID_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_PRICE])) {
            return;
        }
        $transaction->setPrice($this->moneyTransformer->transform($data[self::KEY_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyProductData(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_PRODUCT_DATA])) {
            return;
        }
        if (!is_array($data[self::KEY_PRODUCT_DATA])) {
            return;
        }
        $transaction->setProductData($this->listingPropertyValuesTransformer->transform($data[self::KEY_PRODUCT_DATA]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProductId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_PRODUCT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PRODUCT_ID])) {
            return;
        }
        $transaction->setProductId($data[self::KEY_PRODUCT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantity(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_QUANTITY])) {
            return;
        }
        if (!is_int($data[self::KEY_QUANTITY])) {
            return;
        }
        $transaction->setQuantity($data[self::KEY_QUANTITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReceiptId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_RECEIPT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_RECEIPT_ID])) {
            return;
        }
        $transaction->setReceiptId($data[self::KEY_RECEIPT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySellerUserId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_SELLER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SELLER_USER_ID])) {
            return;
        }
        $transaction->setSellerUserId($data[self::KEY_SELLER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippedTimestamp(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPED_TIMESTAMP])) {
            return;
        }
        $transaction->setShippedTimestamp($data[self::KEY_SHIPPED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShippingCost(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPPING_COST])) {
            return;
        }
        $transaction->setShippingCost($this->moneyTransformer->transform($data[self::KEY_SHIPPING_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingMethod(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_METHOD])) {
            return;
        }
        if (!is_string($data[self::KEY_SHIPPING_METHOD])) {
            return;
        }
        $transaction->setShippingMethod($data[self::KEY_SHIPPING_METHOD]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingProfileId(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_PROFILE_ID])) {
            return;
        }
        $transaction->setShippingProfileId($data[self::KEY_SHIPPING_PROFILE_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingUpgrade(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_SHIPPING_UPGRADE])) {
            return;
        }
        if (!is_string($data[self::KEY_SHIPPING_UPGRADE])) {
            return;
        }
        $transaction->setShippingUpgrade($data[self::KEY_SHIPPING_UPGRADE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopCoupon(Transaction $transaction, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_COUPON])) {
            return;
        }
        $value = self::toFloat($data[self::KEY_SHOP_COUPON]);
        if (null === $value) {
            return;
        }
        $transaction->setShopCoupon($value);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySku(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_SKU])) {
            return;
        }
        if (!is_string($data[self::KEY_SKU])) {
            return;
        }
        $transaction->setSku($data[self::KEY_SKU]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTitle(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_TITLE])) {
            return;
        }
        if (!is_string($data[self::KEY_TITLE])) {
            return;
        }
        $transaction->setTitle($data[self::KEY_TITLE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTransactionType(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_TRANSACTION_TYPE])) {
            return;
        }
        if (!is_string($data[self::KEY_TRANSACTION_TYPE])) {
            return;
        }
        $transaction->setTransactionType($data[self::KEY_TRANSACTION_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyVariations(Transaction $transaction, array $data): void
    {
        if (empty($data[self::KEY_VARIATIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_VARIATIONS])) {
            return;
        }
        $transaction->setVariations($this->transactionVariationsTransformer->transform($data[self::KEY_VARIATIONS]));
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
