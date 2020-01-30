<?php


namespace Salman\ApiExceptionHandler\Concerns;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by Salman Zafar.
 * User: salman
 * Date: 01/30/2020
 * Time: 12:55 PM
 */

class MyValidationException extends Exception
{
    protected $validator;

    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function render()
    {
        return \response([
            'error' => $this->validator->errors()->first()
        ], $this->code);
    }
}
