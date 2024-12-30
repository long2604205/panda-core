<?php

namespace PandaCore\Core\Validator\Rules;

class MinRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        $min = $params[0] ?? 0;
        return is_string($value) && strlen($value) >= $min;
    }

    public function message(string $field, array $params = []): string
    {
        return "The $field must have at least {$params[0]} characters.";
    }
}
