<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class ShopReturnPolicy implements ShopReturnPolicyInterface
{
    private ?bool $acceptsExchanges = null;
    private ?bool $acceptsReturns = null;
    private ?int $returnDeadline = null;
    private int $returnPolicyId;
    private ?int $shopId = null;

    public function __construct(int $returnPolicyId)
    {
        $this->returnPolicyId = $returnPolicyId;
    }

    public function getAcceptsExchanges(): ?bool
    {
        return $this->acceptsExchanges;
    }

    public function getAcceptsReturns(): ?bool
    {
        return $this->acceptsReturns;
    }

    public function getReturnDeadline(): ?int
    {
        return $this->returnDeadline;
    }

    public function getReturnPolicyId(): int
    {
        return $this->returnPolicyId;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setAcceptsExchanges(?bool $value): ShopReturnPolicyInterface
    {
        $this->acceptsExchanges = $value;

        return $this;
    }

    public function setAcceptsReturns(?bool $value): ShopReturnPolicyInterface
    {
        $this->acceptsReturns = $value;

        return $this;
    }

    public function setReturnDeadline(?int $value): ShopReturnPolicyInterface
    {
        $this->returnDeadline = $value;

        return $this;
    }

    public function setReturnPolicyId(int $value): ShopReturnPolicyInterface
    {
        $this->returnPolicyId = $value;

        return $this;
    }

    public function setShopId(?int $value): ShopReturnPolicyInterface
    {
        $this->shopId = $value;

        return $this;
    }
}
