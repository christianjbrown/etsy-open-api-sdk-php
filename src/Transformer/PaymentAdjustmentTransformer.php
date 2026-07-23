<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\PaymentAdjustment;
use ChristianBrown\Etsy\Model\PaymentAdjustmentInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class PaymentAdjustmentTransformer implements PaymentAdjustmentTransformerInterface
{
    private PaymentAdjustmentItemsTransformerInterface $paymentAdjustmentItemsTransformer;

    public function __construct(PaymentAdjustmentItemsTransformerInterface $paymentAdjustmentItemsTransformer)
    {
        $this->paymentAdjustmentItemsTransformer = $paymentAdjustmentItemsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentAdjustmentInterface
    {
        if (!isset($data[self::KEY_PAYMENT_ADJUSTMENT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PAYMENT_ADJUSTMENT_ID));
        }
        if (!is_int($data[self::KEY_PAYMENT_ADJUSTMENT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PAYMENT_ADJUSTMENT_ID));
        }
        $adjustment = new PaymentAdjustment($data[self::KEY_PAYMENT_ADJUSTMENT_ID]);

        self::applyBuyerTotalAdjustmentAmount($adjustment, $data);
        self::applyCreateTimestamp($adjustment, $data);
        self::applyCreatedTimestamp($adjustment, $data);
        self::applyIsSuccess($adjustment, $data);
        self::applyPaymentId($adjustment, $data);
        self::applyReasonCode($adjustment, $data);
        self::applyShopTotalAdjustmentAmount($adjustment, $data);
        self::applyStatus($adjustment, $data);
        self::applyTotalAdjustmentAmount($adjustment, $data);
        self::applyTotalFeeAdjustmentAmount($adjustment, $data);
        self::applyUpdateTimestamp($adjustment, $data);
        self::applyUpdatedTimestamp($adjustment, $data);
        self::applyUserId($adjustment, $data);
        $this->applyPaymentAdjustmentItems($adjustment, $data);

        return $adjustment;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerTotalAdjustmentAmount(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        $adjustment->setBuyerTotalAdjustmentAmount($data[self::KEY_BUYER_TOTAL_ADJUSTMENT_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $adjustment->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $adjustment->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsSuccess(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_IS_SUCCESS])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_SUCCESS])) {
            return;
        }
        $adjustment->setIsSuccess($data[self::KEY_IS_SUCCESS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPaymentAdjustmentItems(PaymentAdjustment $adjustment, array $data): void
    {
        if (empty($data[self::KEY_PAYMENT_ADJUSTMENT_ITEMS])) {
            return;
        }
        if (!is_array($data[self::KEY_PAYMENT_ADJUSTMENT_ITEMS])) {
            return;
        }
        $adjustment->setPaymentAdjustmentItems($this->paymentAdjustmentItemsTransformer->transform($data[self::KEY_PAYMENT_ADJUSTMENT_ITEMS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaymentId(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_PAYMENT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_PAYMENT_ID])) {
            return;
        }
        $adjustment->setPaymentId($data[self::KEY_PAYMENT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReasonCode(PaymentAdjustment $adjustment, array $data): void
    {
        if (empty($data[self::KEY_REASON_CODE])) {
            return;
        }
        if (!is_string($data[self::KEY_REASON_CODE])) {
            return;
        }
        $adjustment->setReasonCode($data[self::KEY_REASON_CODE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopTotalAdjustmentAmount(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        $adjustment->setShopTotalAdjustmentAmount($data[self::KEY_SHOP_TOTAL_ADJUSTMENT_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStatus(PaymentAdjustment $adjustment, array $data): void
    {
        if (empty($data[self::KEY_STATUS])) {
            return;
        }
        if (!is_string($data[self::KEY_STATUS])) {
            return;
        }
        $adjustment->setStatus($data[self::KEY_STATUS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTotalAdjustmentAmount(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_TOTAL_ADJUSTMENT_AMOUNT])) {
            return;
        }
        $adjustment->setTotalAdjustmentAmount($data[self::KEY_TOTAL_ADJUSTMENT_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyTotalFeeAdjustmentAmount(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT])) {
            return;
        }
        if (!is_int($data[self::KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT])) {
            return;
        }
        $adjustment->setTotalFeeAdjustmentAmount($data[self::KEY_TOTAL_FEE_ADJUSTMENT_AMOUNT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $adjustment->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdateTimestamp(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        $adjustment->setUpdateTimestamp($data[self::KEY_UPDATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUserId(PaymentAdjustment $adjustment, array $data): void
    {
        if (!isset($data[self::KEY_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_USER_ID])) {
            return;
        }
        $adjustment->setUserId($data[self::KEY_USER_ID]);
    }
}
