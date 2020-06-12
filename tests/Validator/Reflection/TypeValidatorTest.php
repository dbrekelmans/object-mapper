<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Validator\Reflection;

use DateTime;
use ObjectMapper\Validator\Reflection\TypeValidator;
use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use stdClass;

final class TypeValidatorTest extends TestCase
{
    /**
     * @dataProvider validateValidTypeDataProvider
     *
     * @covers       \ObjectMapper\Validator\Reflection\TypeValidator::isValid
     *
     * @param mixed $value
     */
    public function testValidateValidNamedType($value, ReflectionNamedType $type)
    {
        $validator = new TypeValidator();

        self::assertTrue($validator->isValid($value, $type));
    }

    // TODO: public function testValidateInvalidNamedType($value, ReflectionNamedType $type)

    /** @return array<string, array<mixed>> */
    public function validateValidTypeDataProvider() : array
    {
        return [
            'boolean' => [true, $this->createReflectionNamedType('bool', false)],
            'nullable_boolean' => [true, $this->createReflectionNamedType('bool', true)],
            'null_boolean' => [null, $this->createReflectionNamedType('bool', true)],
            'float' => [1.0, $this->createReflectionNamedType('float', false)],
            'nullable_float' => [1.0, $this->createReflectionNamedType('float', true)],
            'null_float' => [null, $this->createReflectionNamedType('float', true)],
            'integer' => [1, $this->createReflectionNamedType('int', false)],
            'nullable_integer' => [1, $this->createReflectionNamedType('int', true)],
            'null_integer' => [null, $this->createReflectionNamedType('int', true)],
            'string' => ['', $this->createReflectionNamedType('string', false)],
            'nullable_string' => ['', $this->createReflectionNamedType('string', true)],
            'null_string' => [null, $this->createReflectionNamedType('string', true)],
            'object' => [new stdClass(), $this->createReflectionNamedType('object', false)],
            'nullable_object' => [new stdClass(), $this->createReflectionNamedType('object', true)],
            'null_object' => [null, $this->createReflectionNamedType('object', true)],
            'class' => [new stdClass(), $this->createReflectionNamedType('stdClass', false)],
            'nullable_class' => [new stdClass(), $this->createReflectionNamedType('stdClass', true)],
            'null_class' => [null, $this->createReflectionNamedType('stdClass', true)],
            'interface' => [new DateTime(), $this->createReflectionNamedType('DateTimeInterface', false)],
            'nullable_interface' => [new DateTime(), $this->createReflectionNamedType('DateTimeInterface', true)],
            'null_interface' => [null, $this->createReflectionNamedType('DateTimeInterface', true)],
        ];
    }

    private function createReflectionNamedType(string $name, bool $allowsNull) : ReflectionNamedType
    {
        $reflectionType = $this->createStub(ReflectionNamedType::class);

        $reflectionType->method('getName')->willReturn($name);
        $reflectionType->method('allowsNull')->willReturn($allowsNull);

        return $reflectionType;
    }
}
