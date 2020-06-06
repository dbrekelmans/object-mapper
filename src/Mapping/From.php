<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

final class From
{
    public const STATIC = 'static';
    public const PROPERTY = 'property';
    public const METHOD = 'method';

    /** @psalm-var (self::STATIC | self::PROPERTY | self::METHOD) $type */
    private string $type;
    private string $value;

    /**
     * @psalm-param (self::STATIC | self::PROPERTY | self::METHOD) $type
     */
    private function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public static function static(string $value) : self
    {
        return new self(self::STATIC, $value);
    }

    public static function property(string $value) : self
    {
        return new self(self::PROPERTY, $value);
    }

    public static function method(string $value) : self
    {
        return new self(self::METHOD, $value);
    }

    /** @psalm-return (self::STATIC | self::PROPERTY | self::METHOD) */
    public function type() : string
    {
        return $this->type;
    }

    public function value() : string
    {
        return $this->value;
    }
}
