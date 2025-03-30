<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Request;

interface ApiConnectorInterface
{
    public const string FRIENDLY_NAME = 'Etsy\'s API';

    public function get(string $url, array $queryStrings = []): array;
}
