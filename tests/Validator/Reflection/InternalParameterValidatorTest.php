<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Validator\Reflection;

use ObjectMapper\Validator\Reflection\InternalParameterValidator;
use ObjectMapper\Validator\Reflection\ParameterValidatorData;
use ObjectMapper\Validator\Reflection\TypeValidator;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;
use ReflectionType;

final class InternalParameterValidatorTest extends TestCase
{
    private InternalParameterValidator $validator;
    private TypeValidator $typeValidator;

    public function testValidateWithoutType() : void
    {
        $parameter = $this->createStub(ReflectionParameter::class);
        $parameter->method('hasType')->willReturn(false);

        $this->typeValidator->expects(self::never())->method('validate')->willReturnArgument(1);
        $context = $this->validator->validate(ParameterValidatorData::create(0, $parameter));

        self::assertCount(0, $context->violations());
    }

    public function testValidateWithType() : void
    {
        $parameter = $this->createStub(ReflectionParameter::class);
        $parameter->method('hasType')->willReturn(true);
        $parameter->method('getType')->willReturn($this->createStub(ReflectionType::class));

        $this->typeValidator->expects(self::once())->method('validate')->willReturnArgument(1);
        $context = $this->validator->validate(ParameterValidatorData::create(0, $parameter));

        self::assertCount(0, $context->violations());
    }

    protected function setUp() : void
    {
        $this->typeValidator = $this->createMock(TypeValidator::class);

        $this->validator = new InternalParameterValidator($this->typeValidator);
    }


}
