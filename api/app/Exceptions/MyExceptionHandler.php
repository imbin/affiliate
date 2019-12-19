<?php

namespace App\Exceptions;

use App\Enum\CodeEnum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class MyExceptionHandler extends \Illuminate\Foundation\Exceptions\Handler
{

    /**
     * 覆写父类的方法
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Exception $e)
    {
        $code = CodeEnum::BASE_SERVER_ERROR;
        if ($e instanceof \Throwable) {
            $msg = $e->getMessage();
        } else {
            $msg = __('base.server_error');
        }
        $responseData = [
            'code' => is_numeric( $code) ? intval( $code) : $code,
            'msg' => $msg,
            'data' => $this->convertExceptionToArray($e)
        ];
        return new JsonResponse(
            $responseData,
            200,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }


    /**
     * 覆写父类的方法
     * Convert the given exception to an array.
     *
     * @param  \Exception  $e
     * @return array
     */
    protected function convertExceptionToArray(Exception $e)
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ] : [];
    }

    /**
     * 覆写父类的方法
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $errorCode = CodeEnum::BASE_INVALID_PARAMETER;
        $errorMessage = $exception->validator->errors()->first();
        return response()->json([
            'code' => $errorCode,
            'msg' => $errorMessage,
            'data'=> null
        ]);
//        return parent::invalidJson( $request, $exception);
    }

}