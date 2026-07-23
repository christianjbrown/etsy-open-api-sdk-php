<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;

interface ShopReadinessStateDefinitionsTransformerInterface
{
    public const string ARRAY_NAME = 'shop_readiness_state_definition';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopReadinessStateDefinitionInterface>
     */
    public function transform(array $data): array;
}
