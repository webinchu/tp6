<?php
declare (strict_types=1);

namespace app\command;

use app\common\job\MqConsumer;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class RunQueue extends Command
{
    /**
     * 消费队列 php think run-queue 队列名
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('run-queue')
            ->addArgument('queueName', Argument::REQUIRED, '队列名')
            ->setDescription('消费队列');
    }

    protected function execute(Input $input, Output $output)
    {
        $queueName = $input->getArgument('queueName');
        $consumer = new MqConsumer();
        $consumer->start($queueName);//调用消费者
    }
}
