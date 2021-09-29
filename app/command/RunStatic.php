<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class RunStatic extends Command
{
    public static function test($params1, $params2)
    {
        return $params1 . "\n" . $params2;
    }

    /**
     * 执行静态方法 php think run-static 方法绝对路径 参数1 参数2
     * eg php think run-static "\app\command\RunStatic::test" 1 2
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('run-static')
            ->addArgument('methodAndParams', Argument::IS_ARRAY, '参数和方法')
            ->setDescription('执行静态方法');
    }

    protected function execute(Input $input, Output $output)
    {
        $array = $input->getArgument('methodAndParams');
        $methods = explode('::', $array[0]);
        unset($array[0]);
        $array = array_values($array);
        $result = call_user_func_array($methods, $array);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        exit;
    }
}
