<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/9/29
 * Time: 14:42
 */


namespace app\api\controller;


use app\common\service\DocService;
use think\Request;

class Doc
{
    public function index(Request $request)
    {
        $data = $request->param();
        $user = new $data['model']();
        DocService::getDoc($user);
    }
}
