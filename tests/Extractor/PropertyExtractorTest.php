<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ObjectMapper\Extractor\PropertyExtractor;
use PHPUnit\Framework\TestCase;

final class PropertyExtractorTest extends TestCase
{
    private PropertyExtractor $extractor;

    public function testExtractPublicProperty() : void
    {
        $from = new class () {
            public string $property = 'value';
        };

        self::assertSame('value', $this->extractor->extract($from, 'property'));
    }

    public function testExtractPublicStaticProperty() : void
    {
        $from = new class () {
            public static string $property = 'value';
        };

        self::assertSame('value', $this->extractor->extract($from, 'property'));
    }

    protected function setUp() : void
    {
        $this->extractor = new PropertyExtractor();
    }

    public function testExtractProtectedProperty() : void
    {
        $from = new class () {
            protected string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'property');
    }

    public function testExtractProtectedStaticProperty() : void
    {
        $from = new class () {
            protected static string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'property');
    }

    public function testExtractPrivateProperty() : void
    {
        $from = new class () {
            private string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'property');
    }

    public function testExtractPrivateStaticProperty() : void
    {
        $from = new class () {
            private static string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'property');
    }
}
