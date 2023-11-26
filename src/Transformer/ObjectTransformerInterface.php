<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Transformer;

use ChristianBrown\Etsy\Model\ObjectInterface;

interface ObjectTransformerInterface
{
    public function transform(array $data): ObjectInterface;
}
