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
        $from = stdClass::class;
        $to = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new Registry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($from, $to));
    }

    public function testAddDuplicate() : void
    {
        $from = stdClass::class;
        $to = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new Registry();
        $registry->add($mapping);

        $this->expectException(DuplicateEntry::class);
        $registry->add($mapping);
    }

    public function testGet() : void
    {
        $from = stdClass::class;
        $to = Registry::class;

        $mapping = $this->createStub(Mapping::class);
        $mapping->method('from')->willReturn($from);
        $mapping->method('to')->willReturn($to);

        $registry = new Registry();
        $registry->add($mapping);

        self::assertSame($mapping, $registry->get($from, $to));
    }

    public function testGetNotFound() : void
    {
        $registry = new Registry();

        $this->expectException(NotFound::class);
        $registry->get(stdClass::class, Registry::class);
    }
}
