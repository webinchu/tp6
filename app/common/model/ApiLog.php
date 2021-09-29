<?php

namespace app\common\model;

use think\Model;

/**
 * This is the model class for table "api_log".
 * @property integer $id 表id
 * @property string $method 请求方式
 * @property string $url 请求地址
 * @property string $request_data 请求数据
 * @property string $header_data 请求头数据
 * @property string $response 响应
 * @property integer $status 请求状态(0:失败,1:成功)
 * @property string $ip ip地址
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
*/
class ApiLog extends Model {

    /**
     * @var string 表名
     */
    public $tableName = 'api_log';

    /**
     * @var array 本章表的字段
     */
    public $fields = array( 'created_at' => '', 'deleted_at' => '', 'header_data' => '请求头数据', 'id' => '表id', 'ip' => 'ip地址', 'method' => '请求方式', 'request_data' => '请求数据', 'response' => '响应', 'status' => '请求状态(0:失败,1:成功)', 'updated_at' => '', 'url' => '请求地址');

}
