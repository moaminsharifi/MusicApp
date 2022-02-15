<?php

namespace App\Helpers;
use App\Helpers\CustomError;
use Illuminate\Http\Response;



class CustomResponse
{
    public static function createSuccess($data = [])
    {
        $responseData = array(
            'ok' => 1,
            'data' => $data,
        );
        return response($responseData, Response::HTTP_OK);



    }

    public static function createError(string $errorCode  = '00001', $errorData = [])
    {
        $responseData = array(
            'ok' => 0,
            'error' => CustomError::create($errorCode),
            'data' => $errorData,
        );


        return response($responseData, CustomError::getErrorStatus($errorCode));
    }

    public static function createErrorString(string $errorCode, $errorData = [])
    {
        return array(
            'ok' => false,
            'error' => CustomError::create($errorCode),
            'data' => $errorData,
        );


    }

}
