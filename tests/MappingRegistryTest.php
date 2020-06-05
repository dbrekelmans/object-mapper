<?php

declare(strict_types=1);

namespace ObjectMapper\Tests;

use ObjectMapper\Exception\DuplicateEntry;
use ObjectMapper\Exception\NotFound;
use ObjectMapper\Mapping;
use ObjectMapper\MappingRegistry;
use PHPUnit\Framework\TestCase;
use stdClass;

final class MappingRegistryTest extends TestCase
{
    public function testAdd() : void
    {
        $from = stdClass::class;
        $to = MappingRegistry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new MappingRegistry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($from, $to));
    }

    public function testAddDuplicate() : void
    {
        $from = stdClass::class;
        $to = MappingRegistry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new MappingRegistry();
        $registry->add($mapping);

        $this->expectException(DuplicateEntry::class);
        $registry->add($mapping);
    }

    public function testGet() : void
    {
        $from = stdClass::class;
        $to = MappingRegistry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new MappingRegistry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($from, $to));
    }

    public function testGetNotFound() : void
    {
        $registry = new MappingRegistry();

        $this->expectException(NotFound::class);
        $registry->get(stdClass::class, MappingRegistry::class);
    }
}
