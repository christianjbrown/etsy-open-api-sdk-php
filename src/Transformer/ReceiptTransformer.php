<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\Receipt;
use ChristianBrown\Etsy\Model\ReceiptInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class ReceiptTransformer implements ReceiptTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;
    private RefundsTransformerInterface $refundsTransformer;
    private ShipmentsTransformerInterface $shipmentsTransformer;
    private TransactionsTransformerInterface $transactionsTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer, TransactionsTransformerInterface $transactionsTransformer, RefundsTransformerInterface $refundsTransformer, ShipmentsTransformerInterface $shipmentsTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
        $this->transactionsTransformer = $transactionsTransformer;
        $this->refundsTransformer = $refundsTransformer;
        $this->shipmentsTransformer = $shipmentsTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ReceiptInterface
    {
        if (!isset($data[self::KEY_RECEIPT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_RECEIPT_ID));
        }
        if (!is_int($data[self::KEY_RECEIPT_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_RECEIPT_ID));
        }
        $receipt = new Receipt($data[self::KEY_RECEIPT_ID]);

        self::applyBuyerEmail($receipt, $data);
        self::applyBuyerUserId($receipt, $data);
        self::applyCity($receipt, $data);
        self::applyCountryIso($receipt, $data);
        self::applyCreateTimestamp($receipt, $data);
        self::applyCreatedTimestamp($receipt, $data);
        self::applyFirstLine($receipt, $data);
        self::applyFormattedAddress($receipt, $data);
        self::applyGiftMessage($receipt, $data);
        self::applyGiftSender($receipt, $data);
        self::applyIsGift($receipt, $data);
        self::applyIsPaid($receipt, $data);
        self::applyIsShipped($receipt, $data);
        self::applyMessageFromBuyer($receipt, $data);
        self::applyMessageFromPayment($receipt, $data);
        self::applyMessageFromSeller($receipt, $data);
        self::applyName($receipt, $data);
        self::applyPaymentEmail($receipt, $data);
        self::applyPaymentMethod($receipt, $data);
        self::applyReceiptType($receipt, $data);
        self::applySecondLine($receipt, $data);
        self::applySellerEmail($receipt, $data);
        self::applySellerUserId($receipt, $data);
        self::applyState($receipt, $data);
        self::applyStatus($receipt, $data);
        self::applyUpdateTimestamp($receipt, $data);
        self::applyUpdatedTimestamp($receipt, $data);
        self::applyZip($receipt, $data);
        $this->applyGrandtotal($receipt, $data);
        $this->applySubtotal($receipt, $data);
        $this->applyTotalPrice($receipt, $data);
        $this->applyTotalShippingCost($receipt, $data);
        $this->applyTotalTaxCost($receipt, $data);
        $this->applyTotalVatCost($receipt, $data);
        $this->applyDiscountAmt($receipt, $data);
        $this->applyGiftWrapPrice($receipt, $data);
        $this->applyShipments($receipt, $data);
        $this->applyTransactions($receipt, $data);
        $this->applyRefunds($receipt, $data);

        return $receipt;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerEmail(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_BUYER_EMAIL])) {
            return;
        }
        if (!is_string($data[self::KEY_BUYER_EMAIL])) {
            return;
        }
        $receipt->setBuyerEmail($data[self::KEY_BUYER_EMAIL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyBuyerUserId(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_BUYER_USER_ID])) {
            return;
        }
        $receipt->setBuyerUserId($data[self::KEY_BUYER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCity(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_CITY])) {
            return;
        }
        if (!is_string($data[self::KEY_CITY])) {
            return;
        }
        $receipt->setCity($data[self::KEY_CITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCountryIso(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_COUNTRY_ISO])) {
            return;
        }
        if (!is_string($data[self::KEY_COUNTRY_ISO])) {
            return;
        }
        $receipt->setCountryIso($data[self::KEY_COUNTRY_ISO]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $receipt->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreateTimestamp(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATE_TIMESTAMP])) {
            return;
        }
        $receipt->setCreateTimestamp($data[self::KEY_CREATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyDiscountAmt(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_DISCOUNT_AMT])) {
            return;
        }
        if (!is_array($data[self::KEY_DISCOUNT_AMT])) {
            return;
        }
        $receipt->setDiscountAmt($this->moneyTransformer->transform($data[self::KEY_DISCOUNT_AMT]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFirstLine(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_FIRST_LINE])) {
            return;
        }
        if (!is_string($data[self::KEY_FIRST_LINE])) {
            return;
        }
        $receipt->setFirstLine($data[self::KEY_FIRST_LINE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyFormattedAddress(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_FORMATTED_ADDRESS])) {
            return;
        }
        if (!is_string($data[self::KEY_FORMATTED_ADDRESS])) {
            return;
        }
        $receipt->setFormattedAddress($data[self::KEY_FORMATTED_ADDRESS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyGiftMessage(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_GIFT_MESSAGE])) {
            return;
        }
        if (!is_string($data[self::KEY_GIFT_MESSAGE])) {
            return;
        }
        $receipt->setGiftMessage($data[self::KEY_GIFT_MESSAGE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyGiftSender(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_GIFT_SENDER])) {
            return;
        }
        if (!is_string($data[self::KEY_GIFT_SENDER])) {
            return;
        }
        $receipt->setGiftSender($data[self::KEY_GIFT_SENDER]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyGiftWrapPrice(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_GIFT_WRAP_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_GIFT_WRAP_PRICE])) {
            return;
        }
        $receipt->setGiftWrapPrice($this->moneyTransformer->transform($data[self::KEY_GIFT_WRAP_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyGrandtotal(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_GRANDTOTAL])) {
            return;
        }
        if (!is_array($data[self::KEY_GRANDTOTAL])) {
            return;
        }
        $receipt->setGrandtotal($this->moneyTransformer->transform($data[self::KEY_GRANDTOTAL]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsGift(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_IS_GIFT])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_GIFT])) {
            return;
        }
        $receipt->setIsGift($data[self::KEY_IS_GIFT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsPaid(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_IS_PAID])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_PAID])) {
            return;
        }
        $receipt->setIsPaid($data[self::KEY_IS_PAID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsShipped(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_IS_SHIPPED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_SHIPPED])) {
            return;
        }
        $receipt->setIsShipped($data[self::KEY_IS_SHIPPED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMessageFromBuyer(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_MESSAGE_FROM_BUYER])) {
            return;
        }
        if (!is_string($data[self::KEY_MESSAGE_FROM_BUYER])) {
            return;
        }
        $receipt->setMessageFromBuyer($data[self::KEY_MESSAGE_FROM_BUYER]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMessageFromPayment(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_MESSAGE_FROM_PAYMENT])) {
            return;
        }
        if (!is_string($data[self::KEY_MESSAGE_FROM_PAYMENT])) {
            return;
        }
        $receipt->setMessageFromPayment($data[self::KEY_MESSAGE_FROM_PAYMENT]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMessageFromSeller(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_MESSAGE_FROM_SELLER])) {
            return;
        }
        if (!is_string($data[self::KEY_MESSAGE_FROM_SELLER])) {
            return;
        }
        $receipt->setMessageFromSeller($data[self::KEY_MESSAGE_FROM_SELLER]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $receipt->setName($data[self::KEY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaymentEmail(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_PAYMENT_EMAIL])) {
            return;
        }
        if (!is_string($data[self::KEY_PAYMENT_EMAIL])) {
            return;
        }
        $receipt->setPaymentEmail($data[self::KEY_PAYMENT_EMAIL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPaymentMethod(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_PAYMENT_METHOD])) {
            return;
        }
        if (!is_string($data[self::KEY_PAYMENT_METHOD])) {
            return;
        }
        $receipt->setPaymentMethod($data[self::KEY_PAYMENT_METHOD]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReceiptType(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_RECEIPT_TYPE])) {
            return;
        }
        if (!is_int($data[self::KEY_RECEIPT_TYPE])) {
            return;
        }
        $receipt->setReceiptType($data[self::KEY_RECEIPT_TYPE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyRefunds(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_REFUNDS])) {
            return;
        }
        if (!is_array($data[self::KEY_REFUNDS])) {
            return;
        }
        $receipt->setRefunds($this->refundsTransformer->transform($data[self::KEY_REFUNDS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySecondLine(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_SECOND_LINE])) {
            return;
        }
        if (!is_string($data[self::KEY_SECOND_LINE])) {
            return;
        }
        $receipt->setSecondLine($data[self::KEY_SECOND_LINE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySellerEmail(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_SELLER_EMAIL])) {
            return;
        }
        if (!is_string($data[self::KEY_SELLER_EMAIL])) {
            return;
        }
        $receipt->setSellerEmail($data[self::KEY_SELLER_EMAIL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySellerUserId(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_SELLER_USER_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SELLER_USER_ID])) {
            return;
        }
        $receipt->setSellerUserId($data[self::KEY_SELLER_USER_ID]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyShipments(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_SHIPMENTS])) {
            return;
        }
        if (!is_array($data[self::KEY_SHIPMENTS])) {
            return;
        }
        $receipt->setShipments($this->shipmentsTransformer->transform($data[self::KEY_SHIPMENTS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyState(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_STATE])) {
            return;
        }
        if (!is_string($data[self::KEY_STATE])) {
            return;
        }
        $receipt->setState($data[self::KEY_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStatus(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_STATUS])) {
            return;
        }
        if (!is_string($data[self::KEY_STATUS])) {
            return;
        }
        $receipt->setStatus($data[self::KEY_STATUS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applySubtotal(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_SUBTOTAL])) {
            return;
        }
        if (!is_array($data[self::KEY_SUBTOTAL])) {
            return;
        }
        $receipt->setSubtotal($this->moneyTransformer->transform($data[self::KEY_SUBTOTAL]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTotalPrice(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_TOTAL_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_TOTAL_PRICE])) {
            return;
        }
        $receipt->setTotalPrice($this->moneyTransformer->transform($data[self::KEY_TOTAL_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTotalShippingCost(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_TOTAL_SHIPPING_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_TOTAL_SHIPPING_COST])) {
            return;
        }
        $receipt->setTotalShippingCost($this->moneyTransformer->transform($data[self::KEY_TOTAL_SHIPPING_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTotalTaxCost(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_TOTAL_TAX_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_TOTAL_TAX_COST])) {
            return;
        }
        $receipt->setTotalTaxCost($this->moneyTransformer->transform($data[self::KEY_TOTAL_TAX_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTotalVatCost(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_TOTAL_VAT_COST])) {
            return;
        }
        if (!is_array($data[self::KEY_TOTAL_VAT_COST])) {
            return;
        }
        $receipt->setTotalVatCost($this->moneyTransformer->transform($data[self::KEY_TOTAL_VAT_COST]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyTransactions(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_TRANSACTIONS])) {
            return;
        }
        if (!is_array($data[self::KEY_TRANSACTIONS])) {
            return;
        }
        $receipt->setTransactions($this->transactionsTransformer->transform($data[self::KEY_TRANSACTIONS]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdatedTimestamp(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATED_TIMESTAMP])) {
            return;
        }
        $receipt->setUpdatedTimestamp($data[self::KEY_UPDATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyUpdateTimestamp(Receipt $receipt, array $data): void
    {
        if (!isset($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_UPDATE_TIMESTAMP])) {
            return;
        }
        $receipt->setUpdateTimestamp($data[self::KEY_UPDATE_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyZip(Receipt $receipt, array $data): void
    {
        if (empty($data[self::KEY_ZIP])) {
            return;
        }
        if (!is_string($data[self::KEY_ZIP])) {
            return;
        }
        $receipt->setZip($data[self::KEY_ZIP]);
    }
}
