<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\UpdateListingPersonalizationRequestInterface;

interface UpdateListingPersonalizationRequestSerializerInterface
{
    public const string KEY_PERSONALIZATION_QUESTIONS = 'personalization_questions';

    /**
     * @return array<string, mixed>
     */
    public function serialize(UpdateListingPersonalizationRequestInterface $updateListingPersonalizationRequest): array;
}
