<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionInterface;

interface PersonalizationQuestionsTransformerInterface
{
    public const string ARRAY_NAME = 'personalizationQuestion';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, PersonalizationQuestionInterface>
     */
    public function transform(array $data): array;
}
