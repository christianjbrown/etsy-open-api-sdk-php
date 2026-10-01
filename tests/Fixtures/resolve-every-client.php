<?php

declare(strict_types=1);

use ChristianBrown\Etsy\EtsyFactory;
use ChristianBrown\Etsy\EtsyInterface;
use ChristianBrown\KeyValueStore\MemoryKeyValueStore;
use Symfony\Component\Clock\NativeClock;

require dirname(__DIR__, 2).'/vendor/autoload.php';

$etsy = (new EtsyFactory())->create(42, 'test-keystring', 'test-shared-secret', new MemoryKeyValueStore(new NativeClock()), new MemoryKeyValueStore(new NativeClock()));

$resolved = 0;
foreach ((new ReflectionClass(EtsyInterface::class))->getMethods() as $method) {
    if (!str_starts_with($method->getName(), 'get')) {
        continue;
    }
    $type = $method->getReturnType();
    $expected = $type instanceof ReflectionNamedType ? $type->getName() : '';
    $service = $etsy->{$method->getName()}();
    if (!$service instanceof $expected) {
        fwrite(\STDERR, sprintf("%s did not return %s\n", $method->getName(), $expected));
        exit(1);
    }
    ++$resolved;
}

echo sprintf('resolved %d clients', $resolved), "\n";
