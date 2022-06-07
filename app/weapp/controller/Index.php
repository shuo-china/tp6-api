<?php
namespace app\weapp\controller;

use app\common\Send;
use app\common\BaseController;

class Index extends BaseController
{
    use Send;

    public function index()
    {
        $this->success('hello');
    }
}
