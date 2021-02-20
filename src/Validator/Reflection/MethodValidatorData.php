<?php

declare(strict_types=1);

namespace ObjectMapper\Validator\Reflection;

use ReflectionMethod;

use function array_values;

final class MethodValidatorData
{
    /**
     * @psalm-var list<mixed>
     * @var array<mixed>
     */
    private array $arguments;
    private ReflectionMethod $method;

    /** @param array<mixed> $arguments */
    private function __construct(array $arguments, ReflectionMethod $method)
    {
        $this->arguments = array_values($arguments);
        $this->method = $method;
    }

    /**
     * @psalm-param list<mixed> $arguments
     * @param array<mixed> $arguments
     */
    public static function create(array $arguments, ReflectionMethod $method): self
    {
        return new self($arguments, $method);
    }

    /**
     * @psalm-return list<mixed>
     * @return array<mixed>
     */
    public function arguments(): array
    {
        return $this->arguments;
    }

    public function method(): ReflectionMethod
    {
        return $this->method;
    }
}
