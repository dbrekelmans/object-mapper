<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

final class Constructor
{
    /** @var array<Parameter> $parameters */
    private array $parameters;

    /** @param array<Parameter> $parameters */
    private function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @psalm-return array<Parameter> $parameters
     * @return Parameter[] $parameters
     */
    public function parameters() : array
    {
        return $this->parameters;
    }
}
