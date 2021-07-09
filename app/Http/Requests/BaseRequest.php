<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;

abstract class BaseRequest
{
    /**
     * @var array
     */
    protected $validationMessages = array();

    /**
     * Process the request class.
     *
     * @return void
     */
    abstract public function process();

    /**
     * Validates the data and return validation errors
     */
    public function validate(): string
    {
        $validator = Validator::make($this->getValidationData(), $this->getValidationRules());

        foreach ($validator->errors()->messages() as $message) {
            $this->validationMessages[] = (implode('', $message));
        }

        return implode('', $this->validationMessages);
    }


    /**
     * Get validation rules 
     */
    abstract public function getValidationRules(): array;

    /**
     * Get validation data
     */
    abstract public function getValidationData(): array;
}