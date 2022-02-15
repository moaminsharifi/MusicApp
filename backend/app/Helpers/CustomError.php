<?php

namespace App\Helpers;
use Illuminate\Http\Response;

class CustomError
{
    public static $ERROR_STATUS = array(
        'bad_request' => Response::HTTP_BAD_REQUEST, // To be used when
        'not_authorized' => Response::HTTP_UNAUTHORIZED,
        'forbidden' => Response::HTTP_FORBIDDEN,
        'method_not_allowed' => Response::HTTP_METHOD_NOT_ALLOWED,
        'not_found' => Response::HTTP_NOT_FOUND,
        'unprocessable_request' => Response::HTTP_UNPROCESSABLE_ENTITY,
        'large_file' => Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
        'media_type' => Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        'server'=>  Response::HTTP_INTERNAL_SERVER_ERROR,
        'ok'=>  Response::HTTP_OK,
        'conflict'=> Response::HTTP_CONFLICT,
        'created'=> Response::HTTP_CREATED
    );

    public static $ERROR_DATA = array(
        // 00* Basic errors
        '00001' => array(
            'message' => 'Unknown error!',
            'shortener' => 'bad_request',
        ),
        '00002' => array(
            'message' => 'Method not allowed!',
            'shortener' => 'method_not_allowed',
        ),
        '00003' => array(
            'message' => 'Not found!',
            'shortener' => 'not_found',
        ),
        '00004' => array(
            'message' => 'Invalid Input For Submit',
            'shortener' => 'bad_request',
        ),

        /**
         * User Part - 10
         */
        '10001' => array(
            'message' => 'User Not Found',
            'shortener' => 'bad_request',
        ),
        '10002' => array(
            'message' => 'Password Not Correct',
            'shortener' => 'bad_request',
        ),
        '10003' => array(
            'message' => 'User Not Admin',
            'shortener' => 'forbidden',
        ),





    );

    public static function create(string $errorCode = '00001')
    {
        return self::getErrorObject($errorCode);
    }

    public static function getErrorObject(string $errorCode) {
        return array(
            'code' => $errorCode,
            'message' => self::$ERROR_DATA[$errorCode]['message'],
        );
    }

    public static function getErrorStatus(string $errorCode) {
        return self::$ERROR_STATUS[self::$ERROR_DATA[$errorCode]['shortener']];
    }
}
