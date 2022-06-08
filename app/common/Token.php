<?php
namespace app\common;

use think\facade\Request;

class Token 
{
    // AccessToken时效 60 * 60 * 2
    protected static $accessTokenExpires = 5;

    // RefreshToken时效 60 * 60 * 24 * 30
    protected static $refreshTokenExpires = 15;

    /**
     * 设置Token
     * @param mixed $sub  用户标识
     * @param array $info 用户信息    
     */
    public static function setToken($sub, $info = [])
    {
        $scope = app('http')->getName();

        $accessToken = self::buildToken($sub, $info, self::$accessTokenExpires, $scope, 'access');
        $refreshToken = self::buildToken($sub, $info, self::$refreshTokenExpires, $scope, 'refresh');

        $tokenInfo = [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => self::$accessTokenExpires,
            'scope'         => $scope
        ];
        
        return $tokenInfo;
    }

    /**
     * 刷新Token
     * @param string $refreshToken 刷新令牌
     */
    public static function refreshToken($refreshToken)
    {
        $payload = JWT::decode($refreshToken);

        $accessToken = self::buildToken($payload['sub'], $payload['info'], self::$accessTokenExpires, $payload['scope'], 'access');
        
        $tokenInfo = [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => self::$accessTokenExpires,
            'scope'         => $payload['scope']
        ];
        
        return $tokenInfo;
    }

    /**
     * 构建Token
     * @param  mixed  $sub     用户标识
     * @param  array  $info    用户信息
     * @param  int    $expires 时效
     * @param  string $scope   作用域
     * @param  string $role    角色
     * @return string
     */
    private static function buildToken($sub, $info, $expires, $scope, $role)
    {
        $timestamp = time();

        $payload = [
            'iss'   => Request::domain(),
            'iat'   => $timestamp,
            'nbf'   => $timestamp,
            'exp'   => $timestamp + $expires,
            'sub'   => $sub,
            'info'  => $info,
            'scope' => $scope,
            'role'  => $role,
        ];

        return JWT::encode($payload);
    }
}