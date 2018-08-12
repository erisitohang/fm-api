<?php

namespace App\Exceptions;

use Exception;

class ValidationHttpException extends ResourceException
{
    /**
     * ValidationHttpException constructor.
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param Exception|null $previous
     * @param array $headers
     * @param int $code
     */
    public function __construct($errors = null, Exception $previous = null, $headers = [], $code = 0)
    {
        parent::__construct(null, $errors, $previous, $headers, $code);
    }
}
