<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ShopSectionInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ShopSectionsTransformer implements ShopSectionsTransformerInterface
{
    private ShopSectionTransformerInterface $shopSectionTransformer;

    public function __construct(ShopSectionTransformerInterface $shopSectionTransformer)
    {
        $this->shopSectionTransformer = $shopSectionTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ShopSectionInterface>
     */
    public function transform(array $data): array
    {
        $shopSections = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $shopSectionData = $values[$i];
            if (!is_array($shopSectionData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $shopSections[] = $this->shopSectionTransformer->transform($shopSectionData);
        }

        return $shopSections;
    }
}
