<?php

declare(strict_types=1);

namespace ObjectMapper\Validator;

final class Context
{
    /** @var array<Violation> */
    private array $violations = [];

    private function __construct()
    {
    }

    public static function create() : self
    {
        return new self();
    }

    public function add(Violation $violation) : void
    {
        $this->violations[] = $violation;
    }

    /** @return array<Violation> */
    public function violations() : array
    {
        return $this->violations;
    }
}
