<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerFactoryInterface
{
    public function create(): ContainerBuilder;
}
