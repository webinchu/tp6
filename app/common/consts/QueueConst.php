<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/5/28
 * Time: 15:13
 */


namespace app\common\consts;


class QueueConst
{
    const QUEUE_DEFAULT = 'default_queue'; //默认
    const QUEUE_ORDER = 'order_queue'; //订单
    const QUEUE_LOG = 'log_queue'; //日志
    const QUEUE_SMS = 'sms_queue'; //短信

    public static function getQueueNameList(): array
    {
        return [
            self::QUEUE_DEFAULT => '默认队列',
            self::QUEUE_ORDER => '订单队列',
            self::QUEUE_LOG => '日志队列',
            self::QUEUE_SMS => '短信队列',
        ];
    }
}
