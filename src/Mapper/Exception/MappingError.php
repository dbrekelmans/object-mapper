<?php

declare(strict_types=1);

namespace ObjectMapper\Mapper\Exception;

use LogicException;
use ObjectMapper\Validator\Violation;

use function implode;

use const PHP_EOL;

final class MappingError extends LogicException
{
    /** @param array<Violation>|Violation[] $violations */
    public static function violations(array $violations): self
    {
        $violationMessages = [];

        foreach ($violations as $violation) {
            $violationMessages[] = '\t• ' . $violation->message();
        }

        return new self('The following violations were found: ' . PHP_EOL . implode(PHP_EOL, $violationMessages));
    }
}
