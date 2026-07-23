<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopReturnPolicyInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopReturnPoliciesTransformer implements ShopReturnPoliciesTransformerInterface
{
    private ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer;

    public function __construct(ShopReturnPolicyTransformerInterface $shopReturnPolicyTransformer)
    {
        $this->shopReturnPolicyTransformer = $shopReturnPolicyTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopReturnPolicyInterface>
     */
    public function transform(array $data): array
    {
        $shopReturnPolicies = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopReturnPolicyData = $values[$i];
            if (!is_array($shopReturnPolicyData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shopReturnPolicies[] = $this->shopReturnPolicyTransformer->transform($shopReturnPolicyData);
        }

        return $shopReturnPolicies;
    }
}
