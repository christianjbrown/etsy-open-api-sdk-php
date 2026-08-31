<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ScopesInterface;

interface ScopesTransformerInterface
{
    public const string KEY_SCOPES = 'scopes';
    public const string UNEXPECTED_ARRAY_SPRINTF = '%s not set or not an array';

    /**
     * The published OpenAPI document declares the `Scopes` schema with no properties at all, so the
     * `scopes` key this reads comes from what `tokenScopes` actually answers rather than the contract.
     *
     * @param mixed[] $data
     */
    public function transform(array $data): ScopesInterface;
}
