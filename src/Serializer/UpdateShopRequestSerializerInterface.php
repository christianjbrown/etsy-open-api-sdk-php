<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateShopRequestInterface;

interface UpdateShopRequestSerializerInterface
{
    public const string KEY_ANNOUNCEMENT = 'announcement';
    public const string KEY_DIGITAL_SALE_MESSAGE = 'digital_sale_message';
    public const string KEY_POLICY_ADDITIONAL = 'policy_additional';
    public const string KEY_SALE_MESSAGE = 'sale_message';
    public const string KEY_TITLE = 'title';

    /**
     * @return array<string, string>
     */
    public function serialize(UpdateShopRequestInterface $updateShopRequest): array;
}
