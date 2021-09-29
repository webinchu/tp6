<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/9/26
 * Time: 15:09
 */


namespace app\api;


use app\common\service\LogService;
use traits\Curd;

class BaseApi
{
    use Curd;

    /**
     * 操作成功返回的数据
     * @param string $msg 提示信息
     * @param mixed $data 要返回的数据
     * @param int $code 错误码，默认为1
     * @param string $type 输出类型
     * @param array $header 发送的 Header 信息
     */
    public function success($data = null, $msg = '请求成功', $code = 200, $type = null, array $header = [])
    {
        LogService::addLog($data);
        $this->result($msg, $data, $code, $type, $header);
    }

    protected function result($msg, $data = null, $code = 406, $type = null, array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => \think\facade\Request::instance()->server('REQUEST_TIME'),
            'data' => $data,
        ];
        exit(json_encode($result));
    }

    /**
     * 操作失败返回的数据
     * @param string $msg 提示信息
     * @param mixed $data 要返回的数据
     * @param int $code 错误码，默认为0
     * @param string $type 输出类型
     * @param array $header 发送的 Header 信息
     */
    public function error($msg = '请求失败', $data = null, $code = 406, $type = null, array $header = [])
    {
        LogService::addLog($data, 0);
        $this->result($msg, $data, $code, $type, $header);
    }
}
