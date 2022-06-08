<?php
namespace app\common;

use Firebase\JWT\JWT as JWTBase;
use Firebase\JWT\Key;

class JWT 
{
	// jwt密钥
    protected static $secret = 'kirin';

    /**
     * 加密
     * @param  array  $payload
     * @return string
     */
    public static function encode($payload)
    {
    	return JWTBase::encode($payload, self::$secret, 'HS256');
    }

    /**
     * 解密
     * @param  string $jwt
     * @return array
     */
    public static function decode($jwt)
    {
        $decoded = JWTBase::decode($jwt, new Key(self::$secret, 'HS256'));
        
    	return json_decode(json_encode($decoded), true);
    }
}