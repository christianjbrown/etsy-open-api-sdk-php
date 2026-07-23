<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Model;

final class Ping implements PingInterface
{
    private int $applicationId;

    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;
    }

    public function getApplicationId(): int
    {
        return $this->applicationId;
    }

    public function setApplicationId(int $value): PingInterface
    {
        $this->applicationId = $value;

        return $this;
    }
}
