<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeProperty;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;

use function is_array;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class BuyerTaxonomyNodePropertyTransformer implements BuyerTaxonomyNodePropertyTransformerInterface
{
    private BuyerTaxonomyPropertyScalesTransformerInterface $buyerTaxonomyPropertyScalesTransformer;
    private BuyerTaxonomyPropertyValuesTransformerInterface $buyerTaxonomyPropertyValuesTransformer;

    public function __construct(BuyerTaxonomyPropertyScalesTransformerInterface $buyerTaxonomyPropertyScalesTransformer, BuyerTaxonomyPropertyValuesTransformerInterface $buyerTaxonomyPropertyValuesTransformer)
    {
        $this->buyerTaxonomyPropertyScalesTransformer = $buyerTaxonomyPropertyScalesTransformer;
        $this->buyerTaxonomyPropertyValuesTransformer = $buyerTaxonomyPropertyValuesTransformer;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): BuyerTaxonomyNodePropertyInterface
    {
        if (!isset($data[self::KEY_PROPERTY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PROPERTY_ID));
        }
        if (!is_int($data[self::KEY_PROPERTY_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_PROPERTY_ID));
        }
        $property = new BuyerTaxonomyNodeProperty($data[self::KEY_PROPERTY_ID]);

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
    private static function applyDisplayName(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private static function applyIsMultivalued(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private static function applyIsRequired(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private static function applyMaxValuesAllowed(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private static function applyName(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private function applyPossibleValues(BuyerTaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_POSSIBLE_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_POSSIBLE_VALUES])) {
            return;
        }
        $property->setPossibleValues($this->buyerTaxonomyPropertyValuesTransformer->transform($data[self::KEY_POSSIBLE_VALUES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applyScales(BuyerTaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_SCALES])) {
            return;
        }
        if (!is_array($data[self::KEY_SCALES])) {
            return;
        }
        $property->setScales($this->buyerTaxonomyPropertyScalesTransformer->transform($data[self::KEY_SCALES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private function applySelectedValues(BuyerTaxonomyNodeProperty $property, array $data): void
    {
        if (empty($data[self::KEY_SELECTED_VALUES])) {
            return;
        }
        if (!is_array($data[self::KEY_SELECTED_VALUES])) {
            return;
        }
        $property->setSelectedValues($this->buyerTaxonomyPropertyValuesTransformer->transform($data[self::KEY_SELECTED_VALUES]));
    }

    /**
     * @phpstan-param mixed[] $data
     */
    private static function applySupportsAttributes(BuyerTaxonomyNodeProperty $property, array $data): void
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
    private static function applySupportsVariations(BuyerTaxonomyNodeProperty $property, array $data): void
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
