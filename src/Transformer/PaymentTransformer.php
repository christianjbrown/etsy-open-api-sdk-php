<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Payment;
use ChristianBrown\Etsy\Model\PaymentInterface;

use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class PaymentTransformer implements PaymentTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;
    private PaymentAdjustmentsTransformerInterface $paymentAdjustmentsTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer, PaymentAdjustmentsTransformerInterface $paymentAdjustmentsTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
        $this->paymentAdjustmentsTransformer = $paymentAdjustmentsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PaymentInterface
    {
        if (!isset($data[self::KEY_PAYMENT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PAYMENT_ID));
        }
        if (!is_int($data[self::KEY_PAYMENT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PAYMENT_ID));
        }
        $payment = new Payment($data[self::KEY_PAYMENT_ID]);

        self::applyBillingAddressId($payment, $data);
        self::applyBuyerCurrency($payment, $data);
        self::applyBuyerUserId($payment, $data);
        self::applyCreateTimestamp($payment, $data);
        self::applyCreatedTimestamp($payment, $data);
        self::applyCurrency($payment, $data);
        self::applyReceiptId($payment, $data);
        self::applyShippedTimestamp($payment, $data);
        self::applyShippingAddressId($payment, $data);
        self::applyShippingUserId($payment, $data);
        self::applyShopCurrency($payment, $data);
        self::applyShopId($payment, $data);
        self::applyStatus($payment, $data);
        self::applyUpdateTimestamp($payment, $data);
        self::applyUpdatedTimestamp($payment, $data);
        $this->applyAdjustedFees($payment, $data);
        $this->applyAdjustedGross($payment, $data);
        $this->applyAdjustedNet($payment, $data);
        $this->applyAmountFees($payment, $data);
        $this->applyAmountGross($payment, $data);
        $this->applyAmountNet($payment, $data);
        $this->applyPostedFees($payment, $data);
        $this->applyPostedGross($payment, $data);
        $this->applyPostedNet($payment, $data);
        $this->applyPaymentAdjustments($payment, $data);

        return $payment;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAdjustedFees(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_ADJUSTED_FEES])) {
            return;
        }
        if (!is_array($data[self::KEY_ADJUSTED_FEES])) {
            return;
        }
        $payment->setAdjustedFees($this->moneyTransformer->transform($data[self::KEY_ADJUSTED_FEES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAdjustedGross(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_ADJUSTED_GROSS])) {
            return;
        }
        if (!is_array($data[self::KEY_ADJUSTED_GROSS])) {
            return;
        }
        $payment->setAdjustedGross($this->moneyTransformer->transform($data[self::KEY_ADJUSTED_GROSS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAdjustedNet(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_ADJUSTED_NET])) {
            return;
        }
        if (!is_array($data[self::KEY_ADJUSTED_NET])) {
            return;
        }
        $payment->setAdjustedNet($this->moneyTransformer->transform($data[self::KEY_ADJUSTED_NET]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAmountFees(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_AMOUNT_FEES])) {
            return;
        }
        if (!is_array($data[self::KEY_AMOUNT_FEES])) {
            return;
        }
        $payment->setAmountFees($this->moneyTransformer->transform($data[self::KEY_AMOUNT_FEES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAmountGross(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_AMOUNT_GROSS])) {
            return;
        }
        if (!is_array($data[self::KEY_AMOUNT_GROSS])) {
            return;
        }
        $payment->setAmountGross($this->moneyTransformer->transform($data[self::KEY_AMOUNT_GROSS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAmountNet(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_AMOUNT_NET])) {
            return;
        }
        if (!is_array($data[self::KEY_AMOUNT_NET])) {
            return;
        }
        $payment->setAmountNet($this->moneyTransformer->transform($data[self::KEY_AMOUNT_NET]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBillingAddressId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_BILLING_ADDRESS_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BILLING_ADDRESS_ID])) {
            return;
        }
        $payment->setBillingAddressId($data[self::KEY_BILLING_ADDRESS_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerCurrency(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_BUYER_CURRENCY])) {
            return;
        }
        if (!is_string($data[self::KEY_BUYER_CURRENCY])) {
            return;
        }
        $payment->setBuyerCurrency($data[self::KEY_BUYER_CURRENCY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerUserId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        $payment->setBuyerUserId($data[self::KEY_BUYER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $payment->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $payment->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCurrency(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_CURRENCY])) {
            return;
        }
        if (!is_string($data[self::KEY_CURRENCY])) {
            return;
        }
        $payment->setCurrency($data[self::KEY_CURRENCY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPaymentAdjustments(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_PAYMENT_ADJUSTMENTS])) {
            return;
        }
        if (!is_array($data[self::KEY_PAYMENT_ADJUSTMENTS])) {
            return;
        }
        $payment->setPaymentAdjustments($this->paymentAdjustmentsTransformer->transform($data[self::KEY_PAYMENT_ADJUSTMENTS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPostedFees(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_POSTED_FEES])) {
            return;
        }
        if (!is_array($data[self::KEY_POSTED_FEES])) {
            return;
        }
        $payment->setPostedFees($this->moneyTransformer->transform($data[self::KEY_POSTED_FEES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPostedGross(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_POSTED_GROSS])) {
            return;
        }
        if (!is_array($data[self::KEY_POSTED_GROSS])) {
            return;
        }
        $payment->setPostedGross($this->moneyTransformer->transform($data[self::KEY_POSTED_GROSS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPostedNet(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_POSTED_NET])) {
            return;
        }
        if (!is_array($data[self::KEY_POSTED_NET])) {
            return;
        }
        $payment->setPostedNet($this->moneyTransformer->transform($data[self::KEY_POSTED_NET]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReceiptId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_RECEIPT_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_RECEIPT_ID])) {
            return;
        }
        $payment->setReceiptId($data[self::KEY_RECEIPT_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippedTimestamp(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPED_TIMESTAMP])) {
            return;
        }
        $payment->setShippedTimestamp($data[self::KEY_SHIPPED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingAddressId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_ADDRESS_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_ADDRESS_ID])) {
            return;
        }
        $payment->setShippingAddressId($data[self::KEY_SHIPPING_ADDRESS_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShippingUserId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_SHIPPING_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHIPPING_USER_ID])) {
            return;
        }
        $payment->setShippingUserId($data[self::KEY_SHIPPING_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopCurrency(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_SHOP_CURRENCY])) {
            return;
        }
        if (!is_string($data[self::KEY_SHOP_CURRENCY])) {
            return;
        }
        $payment->setShopCurrency($data[self::KEY_SHOP_CURRENCY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $payment->setShopId($data[self::KEY_SHOP_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStatus(Payment $payment, array $data): void
    {
        if (empty($data[self::KEY_STATUS])) {
            return;
        }
        if (!is_string($data[self::KEY_STATUS])) {
            return;
        }
        $payment->setStatus($data[self::KEY_STATUS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $payment->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdateTimestamp(Payment $payment, array $data): void
    {
        if (!isset($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        $payment->setUpdateTimestamp($data[self::KEY_UPDATE_TIMESTAMP]);
    }
}
