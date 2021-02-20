<?php

declare(strict_types=1);

namespace ObjectMapper\Tests\Mapper;

use ObjectMapper\Extractor\Extractor;
use ObjectMapper\Mapper\ConstructorMapper;
use ObjectMapper\Mapping\Argument;
use ObjectMapper\Mapping\Constructor;
use ObjectMapper\Mapping\Source;
use ObjectMapper\Validator\Reflection\InternalMethodValidator;
use PHPUnit\Framework\TestCase;
use stdClass;
use function array_values;

final class ConstructorMapperTest extends TestCase
{
    /**
     * @dataProvider mapDataProvider
     *
     * @psalm-param class-string $target
     * @param array<string, mixed> $expectedProperties
     */
    public function testMap(string $target, array $expectedProperties) : void
    {
        $extractor = $this->createStub(Extractor::class);
        $extractor->method('extract')->willReturnArgument(1);

        $parameters = [];
        foreach ($expectedProperties as $expectedValue) {
            $parameters[] = Argument::create(Source::create($extractor, $expectedValue));
        }

        $methodValidator = $this->createStub(InternalMethodValidator::class);
        $methodValidator->method('isValid')->willReturn(true);

        $mapper = new ConstructorMapper($methodValidator);

        $mappedObject = $mapper->map(new stdClass(), $target, Constructor::create($parameters));

        self::assertEquals(new $target(...array_values($expectedProperties)), $mappedObject);

        foreach ($expectedProperties as $propertyName => $propertyValue) {
            self::assertSame($propertyValue, $mappedObject->$propertyName);
        }
    }

    /** @return array<string, array<mixed>> */
    public function mapDataProvider() : array
    {
        return [
            'zero_parameters' => [ZeroParameterConstructor::class, []],
            'one_parameter' => [OneParameterConstructor::class, ['propertyOne' => 'valueOne']],
            'two_parameter' => [TwoParameterConstructor::class, ['propertyOne' => 'valueOne', 'propertyTwo' => 'valueOne']],
        ];
    }
}

final class ZeroParameterConstructor
{
}

final class OneParameterConstructor
{
    /** @var mixed */
    public $propertyOne;

    /**
     * @param mixed $propertyOne
     */
    public function __construct($propertyOne)
    {
        $this->propertyOne = $propertyOne;
    }
}

final class TwoParameterConstructor
{
    /** @var mixed */
    public $propertyOne;
    /** @var mixed */
    public $propertyTwo;

    /**
     * @param mixed $propertyOne
     * @param mixed $propertyTwo
     */
    public function __construct($propertyOne, $propertyTwo)
    {
        $this->propertyOne = $propertyOne;
        $this->propertyTwo = $propertyTwo;
    }
}
