<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingInventoryProductOffering;
use ChristianBrown\Etsy\Model\ListingInventoryProductOfferingInterface;

use function is_array;
use function is_bool;
use function is_int;
use function sprintf;

final class ListingInventoryProductOfferingTransformer implements ListingInventoryProductOfferingTransformerInterface
{
    private MoneyTransformerInterface $moneyTransformer;

    public function __construct(MoneyTransformerInterface $moneyTransformer)
    {
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingInventoryProductOfferingInterface
    {
        if (!isset($data[self::KEY_OFFERING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_OFFERING_ID));
        }
        if (!is_int($data[self::KEY_OFFERING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_OFFERING_ID));
        }
        $offering = new ListingInventoryProductOffering($data[self::KEY_OFFERING_ID]);

        self::applyQuantity($offering, $data);
        self::applyIsEnabled($offering, $data);
        self::applyIsDeleted($offering, $data);
        $this->applyPrice($offering, $data);
        self::applyReadinessStateId($offering, $data);

        return $offering;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsDeleted(ListingInventoryProductOffering $offering, array $data): void
    {
        if (!isset($data[self::KEY_IS_DELETED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_DELETED])) {
            return;
        }
        $offering->setIsDeleted($data[self::KEY_IS_DELETED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsEnabled(ListingInventoryProductOffering $offering, array $data): void
    {
        if (!isset($data[self::KEY_IS_ENABLED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_ENABLED])) {
            return;
        }
        $offering->setIsEnabled($data[self::KEY_IS_ENABLED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPrice(ListingInventoryProductOffering $offering, array $data): void
    {
        if (empty($data[self::KEY_PRICE])) {
            return;
        }
        if (!is_array($data[self::KEY_PRICE])) {
            return;
        }
        $offering->setPrice($this->moneyTransformer->transform($data[self::KEY_PRICE]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyQuantity(ListingInventoryProductOffering $offering, array $data): void
    {
        if (!isset($data[self::KEY_QUANTITY])) {
            return;
        }
        if (!is_int($data[self::KEY_QUANTITY])) {
            return;
        }
        $offering->setQuantity($data[self::KEY_QUANTITY]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReadinessStateId(ListingInventoryProductOffering $offering, array $data): void
    {
        if (!isset($data[self::KEY_READINESS_STATE_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_READINESS_STATE_ID])) {
            return;
        }
        $offering->setReadinessStateId($data[self::KEY_READINESS_STATE_ID]);
    }
}
