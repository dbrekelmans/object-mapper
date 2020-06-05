<?php

declare(strict_types=1);

namespace ObjectMapper\Mapping;

use ObjectMapper\Mapping\Exception\InvalidType;
use function implode;
use function in_array;
use function sprintf;

final class From
{
    public const VALUE = 'value';
    public const PROPERTY = 'property';
    public const METHOD = 'method';

    /** @psalm-var (self::TYPE_VALUE | self::TYPE_PROPERTY | self::TYPE_METHOD) $type */
    private string $type;
    private string $value;

    /**
     * @psalm-param (self::TYPE_VALUE | self::TYPE_PROPERTY | self::TYPE_METHOD) $type
     *
     * @throws InvalidType
     */
    private function __construct(string $type, string $value)
    {
        $validTypes = [self::VALUE, self::PROPERTY, self::METHOD];
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidType(sprintf('Type "%s" is not valid. Use one of: %s', $type, implode(',', $validTypes)));
        }

        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @psalm-param (self::TYPE_VALUE | self::TYPE_PROPERTY | self::TYPE_METHOD) $type
     *
     * @throws InvalidType
     */
    public static function create(string $type, string $value) : self
    {
        return new self($type, $value);
    }

    /** @psalm-return (self::TYPE_VALUE | self::TYPE_PROPERTY | self::TYPE_METHOD) */
    public function type() : string
    {
        return $this->type;
    }

    public function value() : string
    {
        return $this->value;
    }
}
