<?php

use app\common\consts\QueueConst;
use app\common\job\MqProducer;

/**
 * 一般队列任务
 * 根据回调函数可以分配到不同的队列
 * @param string $callback 方法路径(建议用静态方法)
 * @param array $params 参数
 * @param string $queueName 需要执行的队列名称
 * @see \app\common\consts\QueueConst  //配置信息  \config\rabbitmq.php
 */
function commonQueueJob(string $callback, array $params, string $queueName = QueueConst::QUEUE_DEFAULT)
{
    $message = [
        'callback' => $callback,
        'params' => $params,
        'queueName' => $queueName
    ];
    $message = json_encode($message, (int)true);
    MqProducer::pushMessage($queueName, $message);
    echo '队列投递成功';
}
