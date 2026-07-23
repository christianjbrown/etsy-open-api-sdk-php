<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;

interface ShopReturnPolicyTransformerInterface
{
    public const string KEY_ACCEPTS_EXCHANGES = 'accepts_exchanges';
    public const string KEY_ACCEPTS_RETURNS = 'accepts_returns';
    public const string KEY_RETURN_DEADLINE = 'return_deadline';
    public const string KEY_RETURN_POLICY_ID = 'return_policy_id';
    public const string KEY_SHOP_ID = 'shop_id';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ShopReturnPolicyInterface;
}
