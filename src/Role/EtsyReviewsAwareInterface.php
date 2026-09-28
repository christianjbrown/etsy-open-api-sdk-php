<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Role;

use ChristianBrown\Etsy\Api\ReviewApiInterface;

/**
 * Shop reviews.
 */
interface EtsyReviewsAwareInterface
{
    public function getReviewApi(): ReviewApiInterface;
}
