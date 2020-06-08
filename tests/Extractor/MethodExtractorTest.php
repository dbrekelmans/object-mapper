<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ObjectMapper\Extractor\MethodExtractor;
use PHPUnit\Framework\TestCase;

final class MethodExtractorTest extends TestCase
{
    private MethodExtractor $extractor;

    public function testNonExistentMethod() : void
    {
        $from = new class () {
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'method');
    }

    public function testVoidMethod() : void
    {
        $from = new class () {
            public function method() : void
            {
            }
        };

        self::assertNull($this->extractor->extract($from, 'method'));
    }

    public function testExtractPublicMethod() : void
    {
        $from = new class () {
            public function method() : string
            {
                return 'value';
            }
        };

        self::assertSame('value', $this->extractor->extract($from, 'method'));
    }

    public function testExtractPublicStaticMethod() : void
    {
        $from = new class () {
            public static function method() : string
            {
                return 'value';
            }
        };

        self::assertSame('value', $this->extractor->extract($from, 'method'));
    }

    public function testExtractProtectedMethod() : void
    {
        $from = new class () {
            protected function method() : string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'method');
    }

    public function testExtractProtectedStaticMethod() : void
    {
        $from = new class () {
            protected static function method() : string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'method');
    }

    public function testExtractPrivateMethod() : void
    {
        $from = new class () {
            private function method() : string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'method');
    }

    public function testExtractPrivateStaticMethod() : void
    {
        $from = new class () {
            private static function method() : string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($from, 'method');
    }

    protected function setUp() : void
    {
        $this->extractor = new MethodExtractor();
    }
}
