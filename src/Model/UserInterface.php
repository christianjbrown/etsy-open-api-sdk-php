<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

interface UserInterface
{
    public function getFirstName(): ?string;

    public function getImageUrl75x75(): ?string;

    public function getLastName(): ?string;

    public function getPrimaryEmail(): ?string;

    public function getUserId(): int;

    public function setFirstName(?string $value): self;

    public function setImageUrl75x75(?string $value): self;

    public function setLastName(?string $value): self;

    public function setPrimaryEmail(?string $value): self;

    public function setUserId(int $value): self;
}
