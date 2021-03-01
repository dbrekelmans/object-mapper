<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ObjectMapper\Extractor\MethodExtractor;
use PHPUnit\Framework\TestCase;

final class MethodExtractorTest extends TestCase
{
    private MethodExtractor $extractor;

    public function testNonExistentMethod(): void
    {
        $source = new class () {
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'method');
    }

    public function testVoidMethod(): void
    {
        $source = new class () {
            public function method(): void
            {
            }
        };

        self::assertNull($this->extractor->extract($source, 'method'));
    }

    public function testExtractPublicMethod(): void
    {
        $source = new class () {
            public function method(): string
            {
                return 'value';
            }
        };

        self::assertSame('value', $this->extractor->extract($source, 'method'));
    }

    public function testExtractPublicStaticMethod(): void
    {
        $source = new class () {
            public static function method(): string
            {
                return 'value';
            }
        };

        self::assertSame('value', $this->extractor->extract($source, 'method'));
    }

    public function testExtractProtectedMethod(): void
    {
        $source = new class () {
            protected function method(): string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'method');
    }

    public function testExtractProtectedStaticMethod(): void
    {
        $source = new class () {
            protected static function method(): string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'method');
    }

    public function testExtractPrivateMethod(): void
    {
        $source = new class () {
            private function method(): string
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'method');
    }

    public function testExtractPrivateStaticMethod(): void
    {
        $source = new class () {
            private static function method(): string // phpcs:ignore
            {
                return 'value';
            }
        };

        $this->expectException(NotAccessible::class);
        $this->extractor->extract($source, 'method');
    }

    protected function setUp(): void
    {
        $this->extractor = new MethodExtractor();
    }
}
