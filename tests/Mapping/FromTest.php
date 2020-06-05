<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Mapping;

use ObjectMapper\Mapping\From;
use ObjectMapper\Tests\Mapping\Exception\InvalidType;
use PHPUnit\Framework\TestCase;

final class FromTest extends TestCase
{
    /** @dataProvider fromDataProvider */
    public function testFrom(string $fromType) : void
    {
        From::fromTypeAndValue($fromType, '');
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
        From::fromTypeAndValue('invalid_type', '');
    }
}
