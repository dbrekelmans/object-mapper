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
    public function extract(object $from, string $target)
    {
        try {
            $reflectionMethod = new ReflectionMethod(get_class($from), $target);
        } catch (ReflectionException $e) {
            throw new NotAccessible(sprintf('Method "%s" does not exist.', $target));
        }

        if (!$reflectionMethod->isPublic()) {
            throw new NotAccessible(sprintf('Method "%s" is not public.', $target));
        }

        if ($reflectionMethod->isStatic()) {
            return $from::$target();
        }

        return $from->$target();
    }
}
