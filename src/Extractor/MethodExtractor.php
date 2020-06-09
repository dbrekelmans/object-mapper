<?php

declare(strict_types=1);

namespace ObjectMapper\Extractor;

use ObjectMapper\Extractor\Exception\NotAccessible;
use ReflectionException;
use ReflectionMethod;
use function get_class;
use function sprintf;

final class MethodExtractor implements Extractor
{
    /** @inheritDoc */
    public function extract(object $source, string $data)
    {
        try {
            $reflectionMethod = new ReflectionMethod(get_class($source), $data);
        } catch (ReflectionException $e) {
            throw new NotAccessible(sprintf('Method "%s" does not exist.', $data));
        }

        if (!$reflectionMethod->isPublic()) {
            throw new NotAccessible(sprintf('Method "%s" is not public.', $data));
        }

        if ($reflectionMethod->isStatic()) {
            return $source::$data();
        }

        return $source->$data();
    }
}
