<?php

namespace Core\Validator\Rules;

class EmailRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function message(string $field, array $params = []): string
    {
        return "The $field must be a valid email address.";
    }
}