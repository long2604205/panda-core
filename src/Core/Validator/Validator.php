<?php

namespace PandaCore\Core\Validator;

use PandaCore\Core\Validator\Rules\RuleInterface;
use Exception;

class Validator
{
    private array $data;
    private array $rules;
    private array $messages;
    private array $errors = [];
    private static array $defaultMessages = [
        'required' => 'The :field field is required.',
        'email' => 'The :field must be a valid email address.',
        'min' => 'The :field must have at least :min characters.',
    ];

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public static function make(array $data, array $rules, array $messages = []): Validator
    {
        return new self($data, $rules, $messages);
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;

            foreach ($this->normalizeRules($fieldRules) as $rule) {
                if ($rule instanceof RuleInterface) {
                    if (!$rule->validate($value)) {
                        $this->addError($field, get_class($rule), $rule->message($field));
                    }
                } else {
                    [$ruleName, $params] = $this->parseRule($rule);
                    $ruleClass = $this->getRuleClass($ruleName);
                    if (!$ruleClass->validate($value, $params)) {
                        $this->addError($field, $ruleName, $this->getDefaultMessage($ruleName, $field, $params));
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @throws Exception
     */
    private function normalizeRules($fieldRules): array
    {
        if (is_string($fieldRules)) {
            return explode('|', $fieldRules);
        }

        if (is_array($fieldRules)) {
            return $fieldRules;
        }

        throw new Exception("Invalid rule format for field.");
    }

    private function parseRule(string $rule): array
    {
        if (str_contains($rule, ':')) {
            [$ruleName, $params] = explode(':', $rule, 2);
            $params = explode(',', $params);
        } else {
            $ruleName = $rule;
            $params = [];
        }

        return [$ruleName, $params];
    }

    /**
     * @throws Exception
     */
    private function getRuleClass(string $ruleName): RuleInterface
    {
        $class = "Core\\Validator\\Rules\\" . ucfirst($ruleName) . "Rule";
        if (!class_exists($class)) {
            throw new Exception("Validation rule '$ruleName' does not exist.");
        }

        return new $class;
    }

    private function addError(string $field, string $ruleName, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    private function getDefaultMessage(string $ruleName, string $field, array $params): string
    {
        $message = self::$defaultMessages[$ruleName] ?? 'Invalid value for :field.';
        $replacements = [':field' => $field] + array_combine(
                array_map(fn($p) => ":$p", array_keys($params)),
                $params
            );

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }
}
