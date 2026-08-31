<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\ShopReturnPolicyRequestInterface;

interface ShopReturnPolicyRequestSerializerInterface
{
    public const string KEY_ACCEPTS_EXCHANGES = 'accepts_exchanges';
    public const string KEY_ACCEPTS_RETURNS = 'accepts_returns';
    public const string KEY_RETURN_DEADLINE = 'return_deadline';

    /**
     * @return array<string, string>
     */
    public function serialize(ShopReturnPolicyRequestInterface $shopReturnPolicyRequest): array;
}
