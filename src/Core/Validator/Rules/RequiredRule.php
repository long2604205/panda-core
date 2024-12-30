<?php

namespace PandaCore\Core\Validator\Rules;

class RequiredRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        return !is_null($value) && $value !== '';
    }

    public function message(string $field, array $params = []): string
    {
        return "The $field field is required.";
    }
}