<?php

declare(strict_types=1);

namespace ObjectMapper\Validator;

final class Violation
{
    private string $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function create(string $message) : self
    {
        return new self($message);
    }

    public function message() : string
    {
        return $this->message;
    }
}
