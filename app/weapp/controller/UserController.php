<?php
namespace app\weapp\controller;

use app\common\Send;
use app\common\BaseController;

class UserController extends BaseController
{
    use Send;

    protected $middleware = ['api_auth'];

    public function index()
    {
        $result = [
            'client_id'   => $this->request->clientId,
            'client_info' => $this->request->clientInfo
        ];

        $this->success($result);
    }
}
