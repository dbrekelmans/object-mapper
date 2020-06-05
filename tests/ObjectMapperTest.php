<?php

declare(strict_types=1);

namespace ObjectMapper\Tests;

use ObjectMapper\Mapping;
use ObjectMapper\ObjectMapper;
use PHPUnit\Framework\TestCase;

final class ObjectMapperTest extends TestCase
{
    /**
     * @test
     */
    public function registerAddsMapping() : void
    {
        $objectMapper = new ObjectMapper();

        $mapping = $this->createStub(Mapping::class);

        self::assertNotContains($mapping, $objectMapper->getMappings());
        $objectMapper->register($mapping);
        self::assertContains($mapping, $objectMapper->getMappings());
    }
}
