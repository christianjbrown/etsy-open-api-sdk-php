<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

interface DatasTransformerInterface
{
    public function transform(array $data): array;
}
