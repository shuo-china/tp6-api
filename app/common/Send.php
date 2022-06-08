<?php

namespace app\common;

use think\Response;
use think\exception\HttpResponseException;

trait Send
{
    /**
     * 成功响应
     */
    public static function success($data = '', $header = [])
    {
    	$responseData = [
    		'code'    => 0,
    		'message' => 'ok',
            'data'    => $data
    	];

        self::response($responseData, 200, $header);
    }

    /**
     * 失败响应
     */
    public static function error($code = '', $message = '', $debug = [], $header = [])
    {
        $responseData = [
            'code'    => $code,
            'message' => $message
        ];

        if (env('APP_DEBUG')) {
            $responseData['debug'] = $debug;
        }

        self::response($responseData, 200, $header);
    }

    /**
     * 响应
     * @param mixed $responseData 响应数据
     * @param int   $statusCode   HTTP状态码
     * @param array $header       设置响应头
     */
    public static function response($responseData, $statusCode, $header = [])
    {
        $response = Response::create($responseData, 'json', (int)$statusCode)->header($header);
        throw new HttpResponseException($response);
    }
}