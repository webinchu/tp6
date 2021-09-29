<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/9/26
 * Time: 15:34
 */


namespace app\common\service;


use app\common\consts\QueueConst;
use app\common\model\ApiLog;
use think\facade\Request;
use think\Request as AppRequest;

class LogService
{
    /**
     * 记录api数据
     * @param mixed $responseData 响应数据
     * @param int $status 状态 0:失败 1:成功
     * @return bool
     */
    public static function addLog($responseData, $status = 1)
    {
        //排除不记录日志的接口
        $not = [];
        $notLog = config('not-log.');
        if (!empty($notLog)) {
            foreach ($notLog as $module => $url) {
                $not[] = '/' . $module . '/' . $url;
            }
        }

        $requestUrl = explode('?', $_SERVER['REQUEST_URI']);
        if (isset($requestUrl[0]) && in_array($requestUrl[0], $not)) {
            return true;
        }
        try {
            $body = (new AppRequest())->getContent();
            if (!empty($_POST)) {
                $body = json_encode($_POST, JSON_UNESCAPED_UNICODE);
            }
            $model = new ApiLog();
            $model->url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $model->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : Request::ip();
            $model->status = $status;
            $model->header_data = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
            $model->response = json_encode($responseData, JSON_UNESCAPED_UNICODE);
            $model->request_data = $body;
            $model->method = Request::method();
            $model->created_at = date('Y-m-d H:i:s', time());
            $model->updated_at = date('Y-m-d H:i:s', time());
            /**
             * 扔到队列
             * @see \app\common\service\LogService::apiLogCreate
             */
            commonQueueJob(
                '\app\common\service\LogService::apiLogCreate',
                [$model],
                QueueConst::QUEUE_LOG
            );
//            $model->save();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    //api 日志写入
    public static function apiLogCreate($data)
    {
        $adminLogModel = new ApiLog();
        $adminLogModel->setAttrs($data);
        $adminLogModel->save();
    }
}
