<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopReturnPolicyRequest implements ShopReturnPolicyRequestInterface
{
    private bool $acceptsExchanges;
    private bool $acceptsReturns;
    private ?int $returnDeadline = null;

    public function __construct(bool $acceptsReturns, bool $acceptsExchanges)
    {
        $this->acceptsReturns = $acceptsReturns;
        $this->acceptsExchanges = $acceptsExchanges;
    }

    public function getAcceptsExchanges(): bool
    {
        return $this->acceptsExchanges;
    }

    public function getAcceptsReturns(): bool
    {
        return $this->acceptsReturns;
    }

    public function getReturnDeadline(): ?int
    {
        return $this->returnDeadline;
    }

    public function setAcceptsExchanges(bool $value): ShopReturnPolicyRequestInterface
    {
        $this->acceptsExchanges = $value;

        return $this;
    }

    public function setAcceptsReturns(bool $value): ShopReturnPolicyRequestInterface
    {
        $this->acceptsReturns = $value;

        return $this;
    }

    public function setReturnDeadline(?int $value): ShopReturnPolicyRequestInterface
    {
        $this->returnDeadline = $value;

        return $this;
    }
}
