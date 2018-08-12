<?php

namespace App\Http\Responses;

use App\Exceptions\ValidationHttpException;

class BaseResponse
{
    /**
     * @param $validator
     * @throws ValidationHttpException
     */
    protected function errorBadRequest($validator)
    {
        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        throw new ValidationHttpException($result);
    }
}
