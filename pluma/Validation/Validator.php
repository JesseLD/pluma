<?php

// ==========================================
// File: pluma/Validation/Validator.php
// Description: Validates input data using defined rules
// ==========================================

namespace Pluma\Validation;

class Validator
{
    /**
     * Validate the given data using rules.
     *
     * @param array $data
     * @param array $rules
     * @return array List of error messages (empty if valid)
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $ruleSet);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = 'The field is required.';
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'The field must be a valid email.';
                }

                // More rules can be added here
            }
        }

        return $errors;
    }
}