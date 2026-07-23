<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class User implements UserInterface
{
    private ?string $firstName = null;
    private ?string $imageUrl75x75 = null;
    private ?string $lastName = null;
    private ?string $primaryEmail = null;
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getImageUrl75x75(): ?string
    {
        return $this->imageUrl75x75;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getPrimaryEmail(): ?string
    {
        return $this->primaryEmail;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setFirstName(?string $value): UserInterface
    {
        $this->firstName = $value;

        return $this;
    }

    public function setImageUrl75x75(?string $value): UserInterface
    {
        $this->imageUrl75x75 = $value;

        return $this;
    }

    public function setLastName(?string $value): UserInterface
    {
        $this->lastName = $value;

        return $this;
    }

    public function setPrimaryEmail(?string $value): UserInterface
    {
        $this->primaryEmail = $value;

        return $this;
    }

    public function setUserId(int $value): UserInterface
    {
        $this->userId = $value;

        return $this;
    }
}
