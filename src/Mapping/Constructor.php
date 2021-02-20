<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use function array_values;

final class Constructor
{
    /**
     * @psalm-var list<Argument> $arguments
     * @var array<int, Argument> $arguments
     */
    private array $arguments;

    /**
     * @param array<Argument> $parameters
     */
    private function __construct(array $parameters)
    {
        $this->arguments = array_values($parameters);
    }

    /**
     * @param array<Argument> $arguments
     */
    public static function create(array $arguments): self
    {
        return new self($arguments);
    }

    /**
     * @psalm-return list<Argument>
     * @return array<int, Argument>|Argument[]
     */
    public function arguments(): array
    {
        return $this->arguments;
    }
}
