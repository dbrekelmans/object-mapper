<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ObjectMapper\Extractor\PropertyExtractor;
use PHPUnit\Framework\TestCase;

final class PropertyExtractorTest extends TestCase
{
    private PropertyExtractor $extractor;

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testNonExistentProperty() : void
    {
        $source = new class () {
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testUninitializedProperty() : void
    {
        $source = new class () {
            public string $property;
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractPublicProperty() : void
    {
        $source = new class () {
            public string $property = 'value';
        };

        self::assertSame('value', $this->extractor->extract($source, 'property'));
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractPublicStaticProperty() : void
    {
        $source = new class () {
            public static string $property = 'value';
        };

        self::assertSame('value', $this->extractor->extract($source, 'property'));
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractProtectedProperty() : void
    {
        $source = new class () {
            protected string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractProtectedStaticProperty() : void
    {
        $source = new class () {
            protected static string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractPrivateProperty() : void
    {
        $source = new class () {
            private string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    /** @covers \ObjectMapper\Extractor\PropertyExtractor::extract */
    public function testExtractPrivateStaticProperty() : void
    {
        $source = new class () {
            private static string $property = 'value';
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'property');
    }

    protected function setUp() : void
    {
        $this->extractor = new PropertyExtractor();
    }
}
