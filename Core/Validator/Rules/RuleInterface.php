<?php

namespace Core\Validator\Rules;

interface RuleInterface
{
    /**
     * Validate the given value.
     *
     * @param mixed $value The value to validate.
     * @param array $params Additional parameters for the rule.
     * @return bool True if valid, false otherwise.
     */
    public function validate($value, array $params = []): bool;

    /**
     * Get the error message for this rule.
     *
     * @param string $field The field name.
     * @param array $params Additional parameters for the rule.
     * @return string The error message.
     */
    public function message(string $field, array $params = []): string;
}
