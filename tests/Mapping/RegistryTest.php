<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Mapping;

use ObjectMapper\Mapping\Exception\DuplicateEntry;
use ObjectMapper\Mapping\Exception\NotFound;
use ObjectMapper\Mapping\Mapping;
use ObjectMapper\Mapping\Registry;
use PHPUnit\Framework\TestCase;
use stdClass;

final class RegistryTest extends TestCase
{
    public function testAdd() : void
    {
        $source = stdClass::class;
        $target = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('source')->willReturn($source);
        $mapping->method('target')->willReturn($target);

        $registry = new Registry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($source, $target));
    }

    public function testAddDuplicate() : void
    {
        $source = stdClass::class;
        $target = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('source')->willReturn($source);
        $mapping->method('target')->willReturn($target);

        $registry = new Registry();
        $registry->add($mapping);

        $this->expectException(DuplicateEntry::class);
        $registry->add($mapping);
    }

    public function testGet() : void
    {
        $source = stdClass::class;
        $target = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('source')->willReturn($source);
        $mapping->method('target')->willReturn($target);

        $registry = new Registry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($source, $target));
    }

    public function testGetNotFound() : void
    {
        $registry = new Registry();

        $this->expectException(NotFound::class);
        $registry->get(stdClass::class, Registry::class);
    }
}
