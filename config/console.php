<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        app\command\RunStatic::class,
        app\command\GiiModel::class,
        app\command\RunQueue::class,
    ],
];
