<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ScopesInterface
{
    /**
     * @return array<int, string>
     */
    public function getScopes(): array;

    /**
     * @param array<int, string> $value
     */
    public function setScopes(array $value): self;
}
