<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface ShopReturnPolicyInterface
{
    public function getAcceptsExchanges(): ?bool;

    public function getAcceptsReturns(): ?bool;

    public function getReturnDeadline(): ?int;

    public function getReturnPolicyId(): int;

    public function getShopId(): ?int;

    public function setAcceptsExchanges(?bool $value): self;

    public function setAcceptsReturns(?bool $value): self;

    public function setReturnDeadline(?int $value): self;

    public function setReturnPolicyId(int $value): self;

    public function setShopId(?int $value): self;
}
