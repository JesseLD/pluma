<?php

// ==========================================
// File: pluma/Validation/FormRequest.php
// Description: Base class for validating request input before controller logic
// ==========================================

namespace Pluma\Validation;

abstract class FormRequest
{
    /**
     * The validated input data.
     *
     * @var array
     */
    protected array $data;

    /**
     * The list of validation errors.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Create and validate request data.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errors = Validator::validate($data, $this->rules());
    }

    /**
     * Return validation rules. Must be implemented by subclasses.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Check if request is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get validated input data.
     *
     * @return array
     */
    public function input(): array
    {
        return $this->data;
    }
}
