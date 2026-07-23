<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReadinessStateDefinitionInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopReadinessStateDefinitionsTransformer implements ShopReadinessStateDefinitionsTransformerInterface
{
    private ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer;

    public function __construct(ShopReadinessStateDefinitionTransformerInterface $shopReadinessStateDefinitionTransformer)
    {
        $this->shopReadinessStateDefinitionTransformer = $shopReadinessStateDefinitionTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopReadinessStateDefinitionInterface>
     */
    public function transform(array $data): array
    {
        $shopReadinessStateDefinitions = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopReadinessStateDefinitionData = $values[$i];
            if (!is_array($shopReadinessStateDefinitionData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shopReadinessStateDefinitions[] = $this->shopReadinessStateDefinitionTransformer->transform($shopReadinessStateDefinitionData);
        }

        return $shopReadinessStateDefinitions;
    }
}
