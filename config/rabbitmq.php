<?php
return [
    # 连接信息 一个交换机对应一个队列  可防止重复投递
    'AMQP' => [
        'host' => '127.0.0.1',
        'port' => '5672',
        'login' => 'guest',
        'password' => 'guest',
        'vhost' => '/'
    ],
    # 默认队列
    'default_queue' => [
        'exchange_name' => 'default_exchange',
        'exchange_type' => 'direct',#直连模式
        'queue_name' => 'default_queue',
        'route_key' => 'default_key',
        'consumer_tag' => 'consumer'
    ],
    # 订单队列
    'order_queue' => [
        'exchange_name' => 'order_exchange',
        'exchange_type' => 'direct',#直连模式
        'queue_name' => 'order_queue',
        'route_key' => 'order_key',
        'consumer_tag' => 'consumer'
    ],
    # 日志队列
    'log_queue' => [
        'exchange_name' => 'log_exchange',
        'exchange_type' => 'direct',#直连模式
        'queue_name' => 'log_queue',
        'route_key' => 'log_key',
        'consumer_tag' => 'consumer'
    ],
    # 短信队列
    'sms_queue' => [
        'exchange_name' => 'sms_exchange',
        'exchange_type' => 'direct',#直连模式
        'queue_name' => 'sms_queue',
        'route_key' => 'sms_key',
        'consumer_tag' => 'consumer'
    ]
];
