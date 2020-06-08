<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use function array_values;

final class Constructor
{
    /**
     * @psalm-var list<Parameter> $parameters
     * @var array<int, Parameter> $parameters
     */
    private array $parameters;

    /**
     * @param array<Parameter> $parameters
     */
    private function __construct(array $parameters)
    {
        $this->parameters = array_values($parameters);
    }

    /**
     * @param array<Parameter> $parameters
     */
    public static function create(array $parameters) : self
    {
        return new self($parameters);
    }

    /**
     * @psalm-return list<Parameter> $parameters
     * @return array<int, Parameter>|Parameter[] $parameters
     */
    public function parameters() : array
    {
        return $this->parameters;
    }
}
