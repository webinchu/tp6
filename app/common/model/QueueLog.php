<?php

namespace app\common\model;

use think\Model;

/**
 * This is the model class for table "queue_log".
 * @property integer $id
 * @property string $queue_name 队列名称
 * @property string $callback 回调函数
 * @property string $callback_params 回调函数参数
 * @property string $error_message 错误信息
 * @property integer $status 状态=[0:失败,1:成功];
 * @property integer $is_retry 重试状态=[0:未重试,1:已重试]
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
*/
class QueueLog extends Model {

    /**
     * @var string 表名
     */
    public $tableName = 'queue_log';

    /**
     * @var array 本章表的字段
     */
    public $fields = array( 'callback' => '回调函数', 'callback_params' => '回调函数参数', 'created_at' => '', 'deleted_at' => '', 'error_message' => '错误信息', 'id' => '', 'is_retry' => '重试状态=[0:未重试,1:已重试]', 'queue_name' => '队列名称', 'status' => '状态=[0:失败,1:成功];', 'updated_at' => '');

}
