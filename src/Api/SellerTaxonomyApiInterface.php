<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Api;

use ChristianBrown\Etsy\Model\SellerTaxonomyNodeInterface;
use ChristianBrown\Etsy\Model\TaxonomyNodePropertyInterface;

interface SellerTaxonomyApiInterface extends ApiInterface
{
    public const string API_URL_NODES = 'https://openapi.etsy.com/v3/application/seller-taxonomy/nodes';
    public const string API_URL_PROPERTIES_SPRINTF = 'https://openapi.etsy.com/v3/application/seller-taxonomy/nodes/%d/properties';
    public const string KEY_RESULTS = 'results';
    public const string UNEXPECTED_RESPONSE_SPRINTF = '%s not set or not an array';

    /**
     * Reads the full hierarchy tree of seller taxonomy nodes.
     *
     * @return array<int, SellerTaxonomyNodeInterface>
     */
    public function getNodes(bool $skipCache = false): array;

    /**
     * Reads a list of product properties, with applicable scales and values, supported for a given taxonomy ID.
     *
     * @return array<int, TaxonomyNodePropertyInterface>
     */
    public function getProperties(int $taxonomyId, bool $skipCache = false): array;
}
