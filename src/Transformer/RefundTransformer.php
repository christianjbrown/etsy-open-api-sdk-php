<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\Refund;
use ChristianBrown\Etsy\Model\RefundInterface;

use function is_array;
use function is_int;
use function is_string;

final class RefundTransformer implements RefundTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): RefundInterface
    {
        $refund = new Refund();

        $this->applyAmount($refund, $data);
        self::applyCreatedTimestamp($refund, $data);
        self::applyNoteFromIssuer($refund, $data);
        self::applyReason($refund, $data);
        self::applyStatus($refund, $data);

        return $refund;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyAmount(Refund $refund, array $data): void
    {
        if (empty($data[self::KEY_AMOUNT])) {
            return;
        }
        if (!is_array($data[self::KEY_AMOUNT])) {
            return;
        }
        $refund->setAmount($this->moneyTransformer->transform($data[self::KEY_AMOUNT]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyCreatedTimestamp(Refund $refund, array $data): void
    {
        if (!isset($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        if (!is_int($data[self::KEY_CREATED_TIMESTAMP])) {
            return;
        }
        $refund->setCreatedTimestamp($data[self::KEY_CREATED_TIMESTAMP]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyNoteFromIssuer(Refund $refund, array $data): void
    {
        if (empty($data[self::KEY_NOTE_FROM_ISSUER])) {
            return;
        }
        if (!is_string($data[self::KEY_NOTE_FROM_ISSUER])) {
            return;
        }
        $refund->setNoteFromIssuer($data[self::KEY_NOTE_FROM_ISSUER]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReason(Refund $refund, array $data): void
    {
        if (empty($data[self::KEY_REASON])) {
            return;
        }
        if (!is_string($data[self::KEY_REASON])) {
            return;
        }
        $refund->setReason($data[self::KEY_REASON]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyStatus(Refund $refund, array $data): void
    {
        if (empty($data[self::KEY_STATUS])) {
            return;
        }
        if (!is_string($data[self::KEY_STATUS])) {
            return;
        }
        $refund->setStatus($data[self::KEY_STATUS]);
    }
}
