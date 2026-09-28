<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * One resource group or concern's worth of container wiring. `ContainerFactory` runs every
 * registrar, in order, against the same `ContainerBuilder`, so a registrar may reference a
 * definition registered by one that ran before it, but never one that runs after.
 */
interface ServiceRegistrarInterface
{
    public function register(ContainerBuilder $container): void;
}
