<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ListingPersonalizationInterface;

interface ListingPersonalizationTransformerInterface
{
    public const string KEY_PERSONALIZATION_QUESTIONS = 'personalization_questions';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingPersonalizationInterface;
}
