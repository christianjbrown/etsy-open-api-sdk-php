<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\JsonApiClient\BadResponseTransformerInterface as BaseBadResponseTransformerInterface;

interface BadResponseTransformerInterface extends BaseBadResponseTransformerInterface
{
    public const ERROR_DESCRIPTION_KEY = 'error_description';
    public const FRIENDLY_NAME = 'Etsy\'s API';
    public const MESSAGE_FROM_ERROR_DESCRIPTION = 'Got a %d response from %s: %s';
    public const MESSAGE_GENERIC = 'Got a %d response from %s';
}
