<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Mapping;

use ObjectMapper\Mapping\From;
use ObjectMapper\Mapping\Exception\InvalidType;
use PHPUnit\Framework\TestCase;

final class FromTest extends TestCase
{
    /** @dataProvider fromDataProvider */
    public function testFrom(string $fromType) : void
    {
        From::create($fromType, '');
        $this->addToAssertionCount(1);
    }

    /** @return array<string, array<string>> */
    public function fromDataProvider() : array
    {
        return [
            'value' => ['value'],
            'property' => ['property'],
            'method' => ['method'],
        ];
    }

    public function testFromInvalidType() : void
    {
        $this->expectException(InvalidType::class);
        From::create('invalid_type', '');
    }
}
