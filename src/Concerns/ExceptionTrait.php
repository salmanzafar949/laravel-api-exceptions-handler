<?php
namespace Salman\ApiExceptionHandler\Concerns;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use OutOfBoundsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

/**
 * Created by Salman Zafar.
 * User: salman
 * Date: 01/30/2020
 * Time: 12:52 PM
 */

trait ExceptionTrait {

    public function ApiExceptions($request, $exception)
    {
        if($this->IsModel($exception))
        {
            return $this->ModelResponse($exception);
        }

        if($this->IsHttp($exception))
        {
            return $this->HttpResponse();
        }

        if ($this->IsBound($exception))
        {
            return $this->BoundResponse();
        }

        if ($this->isMethodAllowed($exception))
        {
            return $this->methodAllowedResponse();
        }

        return parent::render($request, $exception);
    }

    protected function IsModel($exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    protected function ModelResponse($exception)
    {
        $resp = str_replace('App\\Model\\', '', $exception->getModel());

        return response()->json([
            "error" => "Model {$resp} Not found"
        ],Response::HTTP_NOT_FOUND);
    }

    protected function IsHttp($exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    protected function HttpResponse()
    {
        return response()->json([
            'error' => 'Url Not Found'
        ],Response::HTTP_NOT_FOUND);
    }

    protected function IsBound($exception)
    {
        return $exception instanceof OutOfBoundsException;
    }

    protected function BoundResponse()
    {
        return response()->json([
            'error' => 'Undefined Index'
        ],Response::HTTP_NOT_FOUND);
    }

    protected function isMethodAllowed($exception)
    {
        return $exception instanceof MethodNotAllowedException;
    }

    protected function methodAllowedResponse()
    {
        return response()->json([
            'error' => 'Method Not Supported'
        ],Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
