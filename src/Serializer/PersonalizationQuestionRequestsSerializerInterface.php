<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;

interface PersonalizationQuestionRequestsSerializerInterface
{
    /**
     * @param array<int, PersonalizationQuestionRequestInterface> $personalizationQuestionRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $personalizationQuestionRequests): array;
}
