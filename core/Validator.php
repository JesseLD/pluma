<?php

namespace Core;

class Validator
{
  public static function requireFields(array $data, array $fields)
  {
    $missing = [];

    foreach ($fields as $field) {
      if (!isset($data[$field]) || $data[$field] === '') {
        $missing[] = $field;
      }
    }

    if (!empty($missing)) {
      Response::json(
        "Missing required fields: " . implode(', ', $missing),
        [],
        Exceptions::VALIDATION['name'],
        Exceptions::VALIDATION['code']
      );
    }
  }
}
