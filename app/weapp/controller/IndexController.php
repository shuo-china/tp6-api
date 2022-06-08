<?php
namespace app\weapp\controller;

use app\common\Send;
use app\common\Token;
use app\common\BaseController;

class IndexController extends BaseController
{
    use Send;

    public function index()
    {
        $result = Token::setToken(1001, ['name' => '张津硕', 'age' => 18]);

        $this->success($result);
    }

    public function refresh()
    {
        $refreshToken = $this->request->post('refresh_token');

        try {
            $result = Token::refreshToken($refreshToken);
        } catch (\Exception $e) {
            $this->error(40001, 'tokon无效');
        }
        
        $this->success($result);
    }
}
