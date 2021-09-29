<?php

namespace app\command;

use mysqli;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use webin\BuildModel;

class GiiModel extends Command
{
    /**
     * 自动生成model类  php think gii-model test@user test 文件夹 user表名
     * 自动生成model类  php think gii-model user   user 表名字
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('gii-model')
            ->addArgument('table', Argument::IS_ARRAY, '表名')
            ->setDescription('自动生成model类');
    }

    protected function execute(Input $input, Output $output)
    {
        $params = $input->getArgument('table');
        if (strpos($params[0],'@')) {
            $newParams = explode('@',$params[0]);
            $dir = $newParams[0];
            $table = $newParams[1];
        } else {
            $table = $params[0];
        }
        if (isset($dir)) {
            $namespace="app\\common\\model\\".$dir;
        } else {
            $namespace="app\\common\\model";
        }
        $mysql = new mysqli(
            env('database.hostname'),
            env('database.username'),
            env('database.password'),
            env('database.database'),
            env('database.hostport')
        );
        $tablePre = env('database.prefix');
        $savePath = 'app/common/model/';
        $basePath = $this->getRootPath() . $savePath;
        if (isset($dir)) {
            $basePath .= $dir . '/';
        }
        $Tii = new BuildModel($mysql, $table, $namespace, $basePath, $tablePre);
        $Tii->create();
        exit;
    }

    public function getRootPath($path = '')
    {
        return app()->getRootPath() . ($path ? $path . '/' : $path);
    }
}
