<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Scopes implements ScopesInterface
{
    /**
     * @var array<int, string>
     */
    private array $scopes = [];

    /**
     * @param array<int, string> $scopes
     */
    public function __construct(array $scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * @return array<int, string>
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param array<int, string> $value
     */
    public function setScopes(array $value): ScopesInterface
    {
        $this->scopes = $value;

        return $this;
    }
}
