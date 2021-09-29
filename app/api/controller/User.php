<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/9/26
 * Time: 15:55
 */


namespace app\api\controller;


use app\api\BaseApi;
use app\common\consts\QueueConst;
use app\common\model\User as UserModel;

class User extends BaseApi
{
    public function __construct(UserModel $model, $relateCondition = '', $relateWhereOr = false)
    {
        parent::__construct($model, $relateCondition, $relateWhereOr);
    }

    public function testQueue()
    {
        commonQueueJob(
            '\app\common\service\LogService::apiLogCreate',  //方法
            [1,2,3], //参数
            QueueConst::QUEUE_LOG //队列
        );
    }
}
