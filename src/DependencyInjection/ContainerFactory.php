<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use function array_walk;

final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var array<int, ServiceRegistrarInterface>
     */
    private array $registrars;

    /**
     * @param array<int, ServiceRegistrarInterface> $registrars run in order against the same container
     */
    public function __construct(array $registrars)
    {
        $this->registrars = $registrars;
    }

    public function create(): ContainerBuilder
    {
        $container = new ContainerBuilder();

        array_walk($this->registrars, static function (ServiceRegistrarInterface $registrar) use ($container): void {
            $registrar->register($container);
        });

        return $container;
    }
}
