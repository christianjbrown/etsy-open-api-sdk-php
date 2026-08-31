<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Serializer;

use ChristianBrown\Etsy\Model\PersonalizationQuestionRequestInterface;

interface PersonalizationQuestionRequestSerializerInterface
{
    public const string KEY_ADD_ON_PRICE = 'add_on_price';
    public const string KEY_INSTRUCTIONS = 'instructions';
    public const string KEY_MAX_ALLOWED_CHARACTERS = 'max_allowed_characters';
    public const string KEY_MAX_ALLOWED_FILES = 'max_allowed_files';
    public const string KEY_OPTIONS = 'options';
    public const string KEY_QUESTION_ID = 'question_id';
    public const string KEY_QUESTION_TEXT = 'question_text';
    public const string KEY_QUESTION_TYPE = 'question_type';
    public const string KEY_REQUIRED = 'required';

    /**
     * @return array<string, mixed>
     */
    public function serialize(PersonalizationQuestionRequestInterface $personalizationQuestionRequest): array;
}
