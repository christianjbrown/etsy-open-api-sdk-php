<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;

interface PersonalizationQuestionOptionsTransformerInterface
{
    public const string ARRAY_NAME = 'personalizationQuestionOption';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * @param mixed[] $data
     *
     * @return array<int, PersonalizationQuestionOptionInterface>
     */
    public function transform(array $data): array;
}
