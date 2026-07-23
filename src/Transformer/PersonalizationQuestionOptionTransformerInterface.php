<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionOptionInterface;

interface PersonalizationQuestionOptionTransformerInterface
{
    public const string KEY_LABEL = 'label';
    public const string KEY_OPTION_ID = 'option_id';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): PersonalizationQuestionOptionInterface;
}
