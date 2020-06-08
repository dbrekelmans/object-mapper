<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

use ObjectMapper\Exception\NotFound;
use ObjectMapper\Exception\ShouldNotHappen;
use ObjectMapper\Extractor\Exception\ExtractionError;
use ObjectMapper\Mapping\From;
use function method_exists;
use function property_exists;
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
        } catch (NotFound|ShouldNotHappen $exception) {
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
     * @throws NotFound
     */
    private function extractFromProperty(From $mapping, object $from)
    {
        $property = $mapping->value();

        if (!property_exists($from, $property)) {
            // TODO: Check if property is public... use Reflection
            throw new NotFound(sprintf('Property "%s" does not exist on provided object.', $property));
        }

        return $from->$property;
    }

    /**
     * @return mixed
     *
     * @throws NotFound
     */
    private function extractFromMethod(From $mapping, object $from)
    {
        $method = $mapping->value();

        if (!method_exists($from, $method)) {
            // TODO: Check if method is public... use Reflection
            throw new NotFound(sprintf('Method "%s" does not exist on provided object.', $method));
        }

        return $from->$method();
    }
}
