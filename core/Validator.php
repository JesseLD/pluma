<?php

namespace Core;

/**
 * Simple validation utility for checking required input fields.
 */
class Validator
{
    /**
     * Checks if required fields are present and not empty in the input data.
     *
     * If any fields are missing or empty, it returns a 400 JSON response
     * listing the fields that are invalid.
     *
     * @param array $data The input data to validate (e.g. $_POST or Request::all()).
     * @param array $fields The list of required field names.
     *
     * @return void
     *
     * Example:
     * Validator::requireFields(Request::all(), ['email', 'password']);
     *
     * Output if missing:
     * {
     *   "error": "Missing required fields.",
     *   "fields": ["email", "password"]
     * }
     */
    public static function requireFields(array $data, array $fields): void
    {
        $missing = [];

        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            Response::json(
                'Missing required fields.',
                ['fields' => $missing],
                400
            );
        }
    }
}
