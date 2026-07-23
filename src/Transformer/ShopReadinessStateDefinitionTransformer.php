<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinition;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;

use function is_int;
use function is_string;
use function sprintf;

final class ShopReadinessStateDefinitionTransformer implements ShopReadinessStateDefinitionTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopReadinessStateDefinitionInterface
    {
        if (!isset($data[self::KEY_READINESS_STATE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_READINESS_STATE_ID));
        }
        if (!is_int($data[self::KEY_READINESS_STATE_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_READINESS_STATE_ID));
        }
        $shopReadinessStateDefinition = new ShopReadinessStateDefinition($data[self::KEY_READINESS_STATE_ID]);

        self::applyMaxProcessingDays($shopReadinessStateDefinition, $data);
        self::applyMinProcessingDays($shopReadinessStateDefinition, $data);
        self::applyProcessingDaysDisplayLabel($shopReadinessStateDefinition, $data);
        self::applyReadinessState($shopReadinessStateDefinition, $data);
        self::applyShopId($shopReadinessStateDefinition, $data);

        return $shopReadinessStateDefinition;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxProcessingDays(ShopReadinessStateDefinition $shopReadinessStateDefinition, array $data): void
    {
        if (!isset($data[self::KEY_MAX_PROCESSING_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_PROCESSING_DAYS])) {
            return;
        }
        $shopReadinessStateDefinition->setMaxProcessingDays($data[self::KEY_MAX_PROCESSING_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMinProcessingDays(ShopReadinessStateDefinition $shopReadinessStateDefinition, array $data): void
    {
        if (!isset($data[self::KEY_MIN_PROCESSING_DAYS])) {
            return;
        }
        if (!is_int($data[self::KEY_MIN_PROCESSING_DAYS])) {
            return;
        }
        $shopReadinessStateDefinition->setMinProcessingDays($data[self::KEY_MIN_PROCESSING_DAYS]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyProcessingDaysDisplayLabel(ShopReadinessStateDefinition $shopReadinessStateDefinition, array $data): void
    {
        if (empty($data[self::KEY_PROCESSING_DAYS_DISPLAY_LABEL])) {
            return;
        }
        if (!is_string($data[self::KEY_PROCESSING_DAYS_DISPLAY_LABEL])) {
            return;
        }
        $shopReadinessStateDefinition->setProcessingDaysDisplayLabel($data[self::KEY_PROCESSING_DAYS_DISPLAY_LABEL]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyReadinessState(ShopReadinessStateDefinition $shopReadinessStateDefinition, array $data): void
    {
        if (empty($data[self::KEY_READINESS_STATE])) {
            return;
        }
        if (!is_string($data[self::KEY_READINESS_STATE])) {
            return;
        }
        $shopReadinessStateDefinition->setReadinessState($data[self::KEY_READINESS_STATE]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyShopId(ShopReadinessStateDefinition $shopReadinessStateDefinition, array $data): void
    {
        if (!isset($data[self::KEY_SHOP_ID])) {
            return;
        }
        if (!is_int($data[self::KEY_SHOP_ID])) {
            return;
        }
        $shopReadinessStateDefinition->setShopId($data[self::KEY_SHOP_ID]);
    }
}
