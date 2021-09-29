<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateQueueLog extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $prefix = env('DATABASE_PREFIX');
        $sql = <<<MYSQL
CREATE TABLE `queue_log` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(32) DEFAULT '' COMMENT '队列名称',
  `callback` varchar(255) DEFAULT '' COMMENT '回调函数',
  `callback_params` text COMMENT '回调函数参数',
  `error_message` text COMMENT '错误信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态=[0:失败,1:成功];',
  `is_retry` tinyint(1) NOT NULL DEFAULT '0' COMMENT '重试状态=[0:未重试,1:已重试]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `callback` (`callback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='队列任务执行记录表';        
MYSQL;
        $this->execute($sql);
    }
}
