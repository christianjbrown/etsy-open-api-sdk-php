<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\BuyerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\BuyerTaxonomyNodePropertyInterface;

interface BuyerTaxonomyApiInterface extends ApiInterface
{
    public const string API_URL_NODES = 'https://openapi.etsy.com/v3/application/buyer-taxonomy/nodes';
    public const string API_URL_PROPERTIES_SPRINTF = 'https://openapi.etsy.com/v3/application/buyer-taxonomy/nodes/%d/properties';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads the full hierarchy tree of buyer taxonomy nodes.
     *
     * @return array<int, BuyerTaxonomyNodeInterface>
     */
    public function getNodes(bool $skipCache = false): array;

    /**
     * Reads a list of product properties, with applicable scales and values, supported for a given taxonomy ID.
     *
     * @return array<int, BuyerTaxonomyNodePropertyInterface>
     */
    public function getProperties(int $taxonomyId, bool $skipCache = false): array;
}
