<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Validator\Reflection;

use DateTime;
use ObjectMapper\Validator\Reflection\InternalTypeValidator;
use ObjectMapper\Validator\Reflection\TypeValidatorData;
use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use ReflectionType;
use stdClass;

final class InternalTypeValidatorTest extends TestCase
{
    private InternalTypeValidator $validator;

    /**
     * @param mixed $value
     *
     * @dataProvider validateValidTypeDataProvider
     */
    public function testValidateValidType($value, ReflectionType $type): void
    {
        $context = $this->validator->validate(TypeValidatorData::create($value, $type));

        self::assertCount(0, $context->violations());
    }

    /** @return array<string, array<mixed>> */
    public function validateValidTypeDataProvider(): array
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

    /**
     * @param mixed $value
     *
     * @dataProvider validateInvalidTypeDataProvider
     */
    public function testValidateInvalidType($value, ReflectionType $type): void
    {
        $context    = $this->validator->validate(TypeValidatorData::create($value, $type));
        $violations = $context->violations();

        self::assertCount(1, $violations);
    }

    /** @return array<string, array<mixed>> */
    public function validateInvalidTypeDataProvider(): array
    {
        return [
            'native_type' => [0, $this->createReflectionNamedType('bool', false)],
            'nullable_native_type' => [0, $this->createReflectionNamedType('bool', true)],
            'null_type' => [null, $this->createReflectionNamedType('bool', false)],
            'class' => [new stdClass(), $this->createReflectionNamedType('DateTime', false)],
            'nullable_class' => [new stdClass(), $this->createReflectionNamedType('DateTime', true)],
            'interface' => [new stdClass(), $this->createReflectionNamedType('DateTimeInterface', false)],
            'nullable_interface' => [new stdClass(), $this->createReflectionNamedType('DateTimeInterface', true)],
        ];
    }

    protected function setUp(): void
    {
        $this->validator = new InternalTypeValidator();
    }

    private function createReflectionNamedType(string $name, bool $allowsNull): ReflectionNamedType
    {
        $reflectionType = $this->createStub(ReflectionNamedType::class);

        $reflectionType->method('getName')->willReturn($name);
        $reflectionType->method('allowsNull')->willReturn($allowsNull);

        return $reflectionType;
    }
}
