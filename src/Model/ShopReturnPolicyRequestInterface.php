<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

/**
 * The body of a `createShopReturnPolicy` or `updateShopReturnPolicy` call. Etsy takes the same
 * fields for both.
 */
interface ShopReturnPolicyRequestInterface
{
    public function getAcceptsExchanges(): bool;

    public function getAcceptsReturns(): bool;

    public function getReturnDeadline(): ?int;

    public function setAcceptsExchanges(bool $value): self;

    public function setAcceptsReturns(bool $value): self;

    public function setReturnDeadline(?int $value): self;
}
