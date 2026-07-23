<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ReviewInterface;

use function array_values;
use function count;
use function is_array;
use function sprintf;

final class ReviewsTransformer implements ReviewsTransformerInterface
{
    private ReviewTransformerInterface $reviewTransformer;

    public function __construct(ReviewTransformerInterface $reviewTransformer)
    {
        $this->reviewTransformer = $reviewTransformer;
    }

    /**
     * @param mixed[] $data
     *
     * @return array<int, ReviewInterface>
     */
    public function transform(array $data): array
    {
        $reviews = [];
        $values = array_values($data);
        for ($i = 0, $count = count($values); $i < $count; ++$i) {
            $reviewData = $values[$i];
            if (!is_array($reviewData)) {
                throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_ARRAY_SPRINTF, self::ARRAY_NAME));
            }
            $reviews[] = $this->reviewTransformer->transform($reviewData);
        }

        return $reviews;
    }
}
