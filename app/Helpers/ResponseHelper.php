<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;

class ResponseHelper
{
    /**
     * Estructure response JSON of success (HTTPCODE 200)
     * 
     * @param array $data 
     * @param string $msg 
     * @param bool $json 
     * @param int $statusCode 
     * @return JsonResponse|(true|string|array)[] 
     *
     *  @throws BindingResolutionException 
     */
    public static function responseSuccess(
        array $data = [], 
        string $msg = "", 
        bool $json = true, 
        int $statusCode = 200
    ): array | JsonResponse
    {
        if ($json) {
            return response()->json(['success' => true, 'msg' => $msg, 'data' => $data], $statusCode);
        } else {
            return ['success' => true, 'msg' => $msg, 'data' => $data, 'status' => $statusCode];
        }
    }

    /**
     * Estructure response JSON of error (HTTPCODE 500)
     * 
     * @param array $data 
     * @param string $msg 
     * @param bool $json 
     * @param int $statusCode 
     * @return JsonResponse|(false|string|array)[] 
     * 
     * @throws BindingResolutionException 
     */
    public static function responseError(
        array $data = [], 
        string $msg = "", 
        bool $json = true, 
        int $statusCode = 500
    ): array | JsonResponse
    {
        if ($json) {
            return response()->json(['success' => false, 'msg' => $msg, 'data' => $data], $statusCode);
        } else {
            return ['success' => false, 'msg' => $msg, 'data' => $data, 'status' => $statusCode];
        }
    }

    /**
     * Estructure response JSON of validade error (HTTPCODE 422)
     * 
     * @param array $data 
     * @param string $msg 
     * @param bool $json 
     * @param int $statusCode 
     * @return JsonResponse|(false|string|array)[] 
     * 
     * @throws BindingResolutionException 
     */
    public static function responseValidateError(
        array $data = [], 
        string $msg = "", 
        bool $json = true, 
        int $statusCode = 422
    ): array | JsonResponse
    {
        if ($json) {
            return \response()->json(['success' => false, 'msg' => $msg, 'data' => $data], $statusCode);
        } else {
            return ['success' => false, 'msg' => $msg, 'data' => $data, 'status' => $statusCode];
        }
    }

    /**
     * Structure response JSON of default (HTTPCODE 204)
     * 
     * @param array $data 
     * @param string $msg 
     * @param bool $json 
     * @param int $status 
     * @return JsonResponse|array 
     * 
     * @throws BindingResolutionException 
     */
    public static function responseDefault(
        array $data = [], 
        string $msg = "", 
        bool $json = true, 
        int $status = 204
    ): array | JsonResponse
    {
        if ($json) {
            return \response()->json(\array_merge(\compact('msg', 'data'), ['success' => false]), $status);
        } else {
            return \array_merge(\compact('msg', 'data', 'status'), ['success' => false]);
        }
    }

}
