<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;

interface TaxonomyNodePropertyTransformerInterface
{
    public const string KEY_DISPLAY_NAME = 'display_name';
    public const string KEY_IS_MULTIVALUED = 'is_multivalued';
    public const string KEY_IS_REQUIRED = 'is_required';
    public const string KEY_MAX_VALUES_ALLOWED = 'max_values_allowed';
    public const string KEY_NAME = 'name';
    public const string KEY_POSSIBLE_VALUES = 'possible_values';
    public const string KEY_PROPERTY_ID = 'property_id';
    public const string KEY_SCALES = 'scales';
    public const string KEY_SELECTED_VALUES = 'selected_values';
    public const string KEY_SUPPORTS_ATTRIBUTES = 'supports_attributes';
    public const string KEY_SUPPORTS_VARIATIONS = 'supports_variations';
    public const string UNEXPECTED_INTEGER_SPRINTF = '%s not set or not an integer';

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TaxonomyNodePropertyInterface;
}
