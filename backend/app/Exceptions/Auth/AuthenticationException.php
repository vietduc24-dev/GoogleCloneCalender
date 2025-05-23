<?php

namespace App\Exceptions\Auth;

use Exception;

class AuthenticationException extends Exception
{
    protected $errors;
    protected $statusCode;

    public function __construct($message = '', $errors = [], $statusCode = 422)
    {
        parent::__construct($message);
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    public function render()
    {
        return response()->json([
            'status' => 'error',
            'errors' => $this->errors ?: ['general' => [$this->message]]
        ], $this->statusCode);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
} 