<?php

declare(strict_types=1);

namespace ChristianBrown\Etsy\Tests\DependencyInjection;

use ChristianBrown\Etsy\DependencyInjection\ContainerFactory;
use ChristianBrown\Etsy\DependencyInjection\ServiceRegistrarInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[CoversClass(ContainerFactory::class)]
final class ContainerFactoryTest extends TestCase
{
    public function testCreateRunsEveryRegistrarAgainstTheSameContainer(): void
    {
        $seenContainers = [];

        $first = self::createMock(ServiceRegistrarInterface::class);
        $first->expects(self::once())->method('register')
            ->with(self::callback(static function (ContainerBuilder $container) use (&$seenContainers): bool {
                $seenContainers[] = $container;

                return true;
            }));

        $second = self::createMock(ServiceRegistrarInterface::class);
        $second->expects(self::once())->method('register')
            ->with(self::callback(static function (ContainerBuilder $container) use (&$seenContainers): bool {
                $seenContainers[] = $container;

                return true;
            }));

        $factory = new ContainerFactory([$first, $second]);
        $container = $factory->create();

        self::assertSame([$container, $container], $seenContainers);
    }

    public function testCreateRunsNoRegistrars(): void
    {
        $factory = new ContainerFactory([]);

        self::assertInstanceOf(ContainerBuilder::class, $factory->create());
    }
}
