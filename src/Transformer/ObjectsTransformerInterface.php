<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

interface ObjectsTransformerInterface
{
    public function transform(array $data): array;
}
