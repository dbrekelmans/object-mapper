<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Exception;

use InvalidArgumentException;

use function sprintf;

final class UnprocessableData extends InvalidArgumentException
{
    /**
     * @psalm-param class-string $expectedClass
     */
    public static function expectedClass(string $expectedClass): self
    {
        return new self(sprintf('Expected data of class "%s".', $expectedClass));
    }
}
