<?php

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/6/18
 * Time: 11:33
 */


namespace app\common\service;

use mysqli;
use think\Model;

class DocService
{
    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';

    public static function getDoc(Model $model)
    {
        $modelFields = $model->fields;
        $mysql = new mysqli(
            env('database.hostname'),
            env('database.username'),
            env('database.password'),
            env('database.database'),
            env('database.hostport')
        );
        $sql = "desc $model->tableName";
        $mysql->set_charset('utf-8');
        $query = $mysql->query($sql);
        $fetch = array();
        while (is_array($row = $query->fetch_array(1))) {
            $fetch[] = $row;
        }
        foreach ($modelFields as $key => $val) {
            if (in_array($key, array_column($fetch, 'Field'))) {
                foreach ($fetch as $k => $v) {
                    if ($v['Field'] == $key) {
                        $fetch[$k]['Comment'] = $val;
                    }
                }
            } else {
                $fetch[count($fetch)] = [
                    'Field' => $key,
                    'Type' => 'varchar(100)',
                    'Comment' => $val
                ];
            }
        }
        $property = array();
        foreach ($fetch as $field) {
            $type = self::getType($field['Type']);
            $type = $type == 'enum' ? self::TYPE_STRING : $type;
            $type = $type == 'tinyint' ? self::TYPE_INT : $type;
            $property[count($property)] = [
                $field['Field'], $type, $field['Comment'] ?? ''
            ];

        }
        echo self::getTable(['字段', '类型', '注释'], $property);
    }

    protected static function getType($typeString)
    {
        list($type) = explode('(', $typeString);
        $types = array(
            self::TYPE_INT => array('int', 'bigint', 'tinyint'),
            self::TYPE_STRING => array('text', 'char', 'varchar', 'decimal', 'longtext', 'mediumtext')
        );

        foreach ($types as $key => $value) {
            if (in_array($type, $value)) {
                return $key;
            }
        }
        return $type;
    }

    public static function getTable($arrTh, $arrTr)
    {
        $s = '<table border="1" cellspacing="0" style="border: 1px solid grey;border-collapse: collapse;border-spacing: 0;">';
        $s .= '<tr>';
        //生成table表头
        for ($i = 0, $m = count($arrTh); $i < $m; $i++) {
            $s .= '<th style="background-color: cadetblue; color: rgb(255, 255, 255);width: 30%">' . $arrTh[$i] . '</th>';
        }
        $s .= '</tr>';
        //判断是否存在数据
        if ($arrTr) {
            //循环输出每行的tr
            for ($i = 0, $k = count($arrTr); $i < $k; $i++) {
                $s .= '<tr>';
                //循环输出每行的td内容
                for ($j = 0, $n = count($arrTr[$i]); $j < $n; $j++) {
                    $s .= '<td style="background-color: rgb(255, 255, 255);padding: 3px">' . $arrTr[$i][$j] . '</td>';
                }
                $s .= '</tr>';
            }
        } else {//不存在数据输出“暂无信息”
            $s .= '<tr>';
            $s .= '<td style="text-align:center;" colspan="' . $m . '">暂无信息</td>';
            $s .= '</tr>';
        }

        $s .= '</table>';
        return $s;
    }
}
