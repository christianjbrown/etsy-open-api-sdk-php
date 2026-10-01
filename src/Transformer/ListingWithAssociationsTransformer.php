<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Exception\UnexpectedResponseException;
use ChristianBrown\Etsy\Model\ListingWithAssociations;
use ChristianBrown\Etsy\Model\ListingWithAssociationsInterface;

use function array_walk;
use function is_int;
use function sprintf;

final class ListingWithAssociationsTransformer implements ListingWithAssociationsTransformerInterface
{
    /**
     * @var array<int, ListingWithAssociationsFieldsTransformerInterface>
     */
    private array $fieldsTransformers;

    /**
     * @param array<int, ListingWithAssociationsFieldsTransformerInterface> $fieldsTransformers applied in order to each listing
     */
    public function __construct(array $fieldsTransformers)
    {
        $this->fieldsTransformers = $fieldsTransformers;
    }

    /**
     * @param mixed[] $data
     */
    public function transform(array $data): ListingWithAssociationsInterface
    {
        if (!isset($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        if (!is_int($data[self::KEY_LISTING_ID])) {
            throw new UnexpectedResponseException(sprintf(self::UNEXPECTED_INTEGER_SPRINTF, self::KEY_LISTING_ID));
        }
        $listing = new ListingWithAssociations($data[self::KEY_LISTING_ID]);

        array_walk($this->fieldsTransformers, static function (ListingWithAssociationsFieldsTransformerInterface $fieldsTransformer) use ($listing, $data): void {
            $fieldsTransformer->apply($listing, $data);
        });

        return $listing;
    }
}
