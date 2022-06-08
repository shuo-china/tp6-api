<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\JWT;
use app\common\Send;

class ApiAuth
{
    use Send;

    /**
     * 处理请求
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $payload = $this->authenticate($request);

        $request->clientId = $payload['sub'];
        $request->clientInfo = $payload['info'];

        return $next($request);
    }

    /**
     * 认证授权
     * @param \think\Request $request
     */
    private function authenticate($request)
    {
        $accessToken = $this->getAccessToken($request);

        $payload = $this->certification($accessToken);

        if (app('http')->getName() !== $payload['scope']) {
            $this->error(4003, '没有权限');
        }

        return $payload;
    }

    /**
     * 获取请求头中的AccessToken
     * @param \think\Request $request
     * @return string
     */
    private function getAccessToken($request)
    {
        $authorization = $request->header('Authorization');

        if (preg_match('/^Bearer\s(\S+)/', $authorization, $matches)) {
            return $matches[1];
        }

        $this->error(4001,  'token无效');
    }

    /**
     * 鉴定AccessToken
     * @param string $accessToken
     * @return array
     */
    private function certification($accessToken)
    {
        try {
            return JWT::decode($accessToken);
        } catch (\Exception $e) {
            $this->error(4001, 'token无效');
        }
    }
}
