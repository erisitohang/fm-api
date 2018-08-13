<?php

namespace App\Http\Responses;

use App\Exceptions\ValidationHttpException;
use Illuminate\Http\Request;

class BaseResponse
{
    const NUMBER_OF_EMAILS_REQUIRED = 2;
    const SUCCESS = "success";
    const FRIENDS = "friends";
    const COUNT = "count";
    const FIELD = 'field';
    const CODE = 'code';
    const EMAIL = 'email';
    const NAME = 'name';
    const EMAILS_ARE_REQUIRED = 'Emails are required';

    /**
     * This method is the temporary solution to resolve the issue
     * Issue: https://github.com/laravel/lumen-framework/issues/667
     * TODO! Remove this line after the issue is resolved
     */
    protected function getRequest()
    {
        return app(Request::class);
    }

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
                        self::FIELD => $field,
                        self::CODE => $error,
                    ];
                }
            }
        }
        throw new ValidationHttpException($result);
    }
}
