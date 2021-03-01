<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Extractor;

use ObjectMapper\Extractor\StaticExtractor;
use PHPUnit\Framework\TestCase;
use stdClass;

final class StaticExtractorTest extends TestCase
{
    public function testExtract(): void
    {
        $expected = 'static value';
        $actual   = (new StaticExtractor())->extract(new stdClass(), $expected);

        self::assertSame($expected, $actual);
    }
}
