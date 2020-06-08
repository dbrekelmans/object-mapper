<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

use ObjectMapper\Exception\ShouldNotHappen;
use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapping\From;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use function get_class;
use function sprintf;

final class ValueExtractor implements Extractor
{
    /** @inheritDoc */
    public function extract(object $from, From $mapping)
    {
        $type = $mapping->type();

        try {
            switch ($type) {
                case From::STATIC:
                    return $this->extractFromStatic($mapping);
                case From::PROPERTY:
                    return $this->extractFromProperty($mapping, $from);
                case From::METHOD:
                    return $this->extractFromMethod($mapping, $from);
                default:
                    throw ShouldNotHappen::unreachable(sprintf('Mapping type "%s" is not handled.', $type));
            }
        } catch (ShouldNotHappen $exception) {
            throw new ExtractionError('Unable to extract ', 0, $exception);
        }
    }

    private function extractFromStatic(From $mapping) : string
    {
        return $mapping->value();
    }

    /**
     * @return mixed
     *
     * @throws ExtractionError
     */
    private function extractFromProperty(From $mapping, object $from)
    {
        $property = $mapping->value();

        try {
            $reflectionProperty = new ReflectionProperty(get_class($from), $property);
        } catch (ReflectionException $exception) {
            throw new ExtractionError(sprintf('Property "%s" does not exist.', $property));
        }

        if (!$reflectionProperty->isPublic()) {
            throw new ExtractionError(sprintf('Property "%s" is not public.', $property));
        }

        if ($reflectionProperty->isStatic()) {
            return $from::$property;
        }

        return $from->$property;
    }

    /**
     * @return mixed
     *
     * @throws ExtractionError
     */
    private function extractFromMethod(From $mapping, object $from)
    {
        $method = $mapping->value();

        try {
            $reflectionMethod = new ReflectionMethod(get_class($from), $method);
        } catch (ReflectionException $e) {
            throw new ExtractionError(sprintf('Method "%s" does not exist.', $method));
        }

        if (!$reflectionMethod->isPublic()) {
            throw new ExtractionError(sprintf('Method "%s" is not public.', $method));
        }

        if ($reflectionMethod->isStatic()) {
            return $from::$method();
        }

        return $from->$method();
    }
}
