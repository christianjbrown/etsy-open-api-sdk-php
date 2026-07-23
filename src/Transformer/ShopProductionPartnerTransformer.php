<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopProductionPartner;
use ChristianBrown\Etsy\Model\ShopProductionPartnerInterface;

use function is_int;
use function is_string;
use function sprintf;

final class ShopProductionPartnerTransformer implements ShopProductionPartnerTransformerInterface
{
    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopProductionPartnerInterface
    {
        if (!isset($data[self::KEY_PRODUCTION_PARTNER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PRODUCTION_PARTNER_ID));
        }
        if (!is_int($data[self::KEY_PRODUCTION_PARTNER_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PRODUCTION_PARTNER_ID));
        }
        $shopProductionPartner = new ShopProductionPartner($data[self::KEY_PRODUCTION_PARTNER_ID]);

        self::applyLocation($shopProductionPartner, $data);
        self::applyPartnerName($shopProductionPartner, $data);

        return $shopProductionPartner;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyLocation(ShopProductionPartner $shopProductionPartner, array $data): void
    {
        if (empty($data[self::KEY_LOCATION])) {
            return;
        }
        if (!is_string($data[self::KEY_LOCATION])) {
            return;
        }
        $shopProductionPartner->setLocation($data[self::KEY_LOCATION]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyPartnerName(ShopProductionPartner $shopProductionPartner, array $data): void
    {
        if (empty($data[self::KEY_PARTNER_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_PARTNER_NAME])) {
            return;
        }
        $shopProductionPartner->setPartnerName($data[self::KEY_PARTNER_NAME]);
    }
}
