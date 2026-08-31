<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionRequestInterface;

interface PersonalizationQuestionOptionRequestSerializerInterface
{
    public const string KEY_LABEL = 'label';
    public const string KEY_OPTION_ID = 'option_id';

    /**
     * @return array<string, mixed>
     */
    public function serialize(PersonalizationQuestionOptionRequestInterface $personalizationQuestionOptionRequest): array;
}
