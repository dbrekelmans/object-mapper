<?php

declare(strict_types=1);

namespace ObjectMapper\Exception;

use LogicException;
use function sprintf;

final class ShouldNotHappen extends LogicException
{
    private function __construct(string $message = '')
    {
        parent::__construct($message);
    }

    public static function unreachable(string $context) : self
    {
        return new self(sprintf('This code should not be reachable. Context: %s', $context));
    }
}
