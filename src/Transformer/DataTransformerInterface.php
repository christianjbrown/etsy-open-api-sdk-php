<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ModelInterface;

interface DataTransformerInterface
{
    public function transform(array $data): ModelInterface;
}
