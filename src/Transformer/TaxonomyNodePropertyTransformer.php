<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\TaxonomyNodeProperty;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class TaxonomyNodePropertyTransformer implements TaxonomyNodePropertyTransformerInterface
{
    private TaxonomyPropertyScalesTransformerInterface $taxonomyPropertyScalesTransformer;
    private TaxonomyPropertyValuesTransformerInterface $taxonomyPropertyValuesTransformer;

    public function __construct(TaxonomyPropertyScalesTransformerInterface $taxonomyPropertyScalesTransformer, TaxonomyPropertyValuesTransformerInterface $taxonomyPropertyValuesTransformer)
    {
        $this->taxonomyPropertyScalesTransformer = $taxonomyPropertyScalesTransformer;
        $this->taxonomyPropertyValuesTransformer = $taxonomyPropertyValuesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): TaxonomyNodePropertyInterface
    {
        if (!isset($data[self::KEY_PROPERTY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PROPERTY_ID));
        }
        if (!is_int($data[self::KEY_PROPERTY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PROPERTY_ID));
        }
        $property = new TaxonomyNodeProperty($data[self::KEY_PROPERTY_ID]);

        self::applyDisplayName($property, $data);
        self::applyIsMultivalued($property, $data);
        self::applyIsRequired($property, $data);
        self::applyMaxValuesAllowed($property, $data);
        self::applyName($property, $data);
        $this->applyPossibleValues($property, $data);
        $this->applyScales($property, $data);
        $this->applySelectedValues($property, $data);
        self::applySupportsAttributes($property, $data);
        self::applySupportsVariations($property, $data);

        return $property;
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyDisplayName(TaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_DISPLAY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_DISPLAY_NAME])) {
            return;
        }
        $property->setDisplayName($data[self::KEY_DISPLAY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsMultivalued(TaxonomyNodeProperty $property, array $data): void
    {
        if (!isset($data[self::KEY_IS_MULTIVALUED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_MULTIVALUED])) {
            return;
        }
        $property->setIsMultivalued($data[self::KEY_IS_MULTIVALUED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyIsRequired(TaxonomyNodeProperty $property, array $data): void
    {
        if (!isset($data[self::KEY_IS_REQUIRED])) {
            return;
        }
        if (!is_bool($data[self::KEY_IS_REQUIRED])) {
            return;
        }
        $property->setIsRequired($data[self::KEY_IS_REQUIRED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyMaxValuesAllowed(TaxonomyNodeProperty $property, array $data): void
    {
        if (!isset($data[self::KEY_MAX_VALUES_ALLOWED])) {
            return;
        }
        if (!is_int($data[self::KEY_MAX_VALUES_ALLOWED])) {
            return;
        }
        $property->setMaxValuesAllowed($data[self::KEY_MAX_VALUES_ALLOWED]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applyName(TaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_NAME])) {
            return;
        }
        if (!is_string($data[self::KEY_NAME])) {
            return;
        }
        $property->setName($data[self::KEY_NAME]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyPossibleValues(TaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_POSSIBLE_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_POSSIBLE_VALUES])) {
            return;
        }
        $property->setPossibleValues($this->taxonomyPropertyValuesTransformer->transform($data[self::KEY_POSSIBLE_VALUES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyScales(TaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_SCALES])) {
            return;
        }
        if (!is_array($data[self::KEY_SCALES])) {
            return;
        }
        $property->setScales($this->taxonomyPropertyScalesTransformer->transform($data[self::KEY_SCALES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applySelectedValues(TaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_SELECTED_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_SELECTED_VALUES])) {
            return;
        }
        $property->setSelectedValues($this->taxonomyPropertyValuesTransformer->transform($data[self::KEY_SELECTED_VALUES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySupportsAttributes(TaxonomyNodeProperty $property, array $data): void
    {
        if (!isset($data[self::KEY_SUPPORTS_ATTRIBUTES])) {
            return;
        }
        if (!is_bool($data[self::KEY_SUPPORTS_ATTRIBUTES])) {
            return;
        }
        $property->setSupportsAttributes($data[self::KEY_SUPPORTS_ATTRIBUTES]);
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySupportsVariations(TaxonomyNodeProperty $property, array $data): void
    {
        if (!isset($data[self::KEY_SUPPORTS_VARIATIONS])) {
            return;
        }
        if (!is_bool($data[self::KEY_SUPPORTS_VARIATIONS])) {
            return;
        }
        $property->setSupportsVariations($data[self::KEY_SUPPORTS_VARIATIONS]);
    }
}
