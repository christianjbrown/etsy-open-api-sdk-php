<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;

interface PersonalizationQuestionOptionRequestsSerializerInterface
{
    /**
     * @param array<int, PersonalizationQuestionOptionRequestInterface> $personalizationQuestionOptionRequests
     *
     * @return array<int, array<string, mixed>>
     */
    public function serialize(array $personalizationQuestionOptionRequests): array;
}
