<?php

namespace app\common\model;

use think\Model;

/**
 * This is the model class for table "user".
 * @property integer $id
 * @property string $name 用户名
 * @property integer $sex 性别
*/
class User extends Model {

    /**
     * @var string 表名
     */
    public $tableName = 'user';

    /**
     * @var array 本章表的字段
     */
    public $fields = array( 'id' => '', 'name' => '用户名', 'sex' => '性别');

}
