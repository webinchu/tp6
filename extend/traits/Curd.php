<?php

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/6/7
 * Time: 14:26
 */


namespace traits;

use app\common\service\LogService;
use think\App;
use think\Model;
use think\Request;

trait Curd
{
    protected $searchFields = 'id';
    protected $relateCondition = '';    //关联表的ON条件 相当于:A表 left join B表 on $relateCondition
    protected $relateWhereOr = false;   //关联表查询的条件为OR true:A表条件 OR B表条件 false:A表条件 AND B表条件


    /**
     * 构造方法
     * @access public
     * @param Model $model 模型
     */
    public function __construct(Model $model, $relateCondition = '', $relateWhereOr = false)
    {
        $this->modelClass = $model;
        $this->relateCondition = $relateCondition;
        $this->relateWhereOr = $relateWhereOr;
    }

    //列表
    public function index(Request $request)
    {
        list($this->page, $this->pageSize, $sort, $where) = $this->buildParames(null, null, $request->withStatus);
        if (!empty($request->andWhere)) {
            $where = array_merge($where, $request->andWhere);
        }
        //这一步是为了联表查询
        $modelName = '';
        $relateWhere = [];
        if (!empty($where)) {
            foreach ($where as $key => &$item) {
                if (isset($item[0]) && $item[0]) {
                    if (is_string($item[0]) && strstr($item[0], '.')) {
                        $modelName = isset(explode('.', $item[0])[0]) ? explode('.', $item[0])[0] : '';
                        if ($this->relateWhereOr) {
                            array_push($relateWhere, $item);
                            unset($where[$key]);
                        }
                    } else {
                        $item[0] = $this->modelClass->getTable() . '.' . $item[0];
                    }
                }
            }
        }
        sort($where);
        $build = $this->modelClass
            ->where($where);
        if ($modelName) {
            $build->leftJoin(
                $modelName, $this->relateCondition
            );
            if (!empty($relateWhere)) {
                $build->where($relateWhere);
            }
        }
        $count = $build->count();
//        echo $count = $build->buildSql();exit();
        if (!empty($request->sortConditions)) {
            $build->order($request->sortConditions);
        }
        $list = $build
            ->order($sort)
            ->page($this->page, $this->pageSize)
            ->select();
        return $this->returnDataJson($list, $count);
    }

    //查看

    /**
     * 组合参数
     * @param null $searchfields
     * @param null $relationSearch
     * @param bool $withStatus
     * @return array
     */
    protected function buildParames($searchFields = null, $relationSearch = null, $withStatus = true)
    {
        $this->request = (new App())->request;
        header("content-type:text/html;charset=utf-8"); //设置编码
        $page = $this->request->param('page/d', 1);
        $limit = $this->request->param('limit/d', 500);
        $filters = $this->request->get('filter', '{}');
        $ops = $this->request->param('op', '{}');
        $name = $this->modelClass->getTable();
        $tableName = $name . '.';
        $sort = $this->request->get("sort", !empty($this->modelClass) && $this->modelClass->getPk() ? $this->modelClass->getPk() : $tableName . '.id');
        $sort = $tableName . $sort;
        $order = $this->request->get("order", "DESC");
//        $filters = htmlspecialchars_decode(iconv('GBK','utf-8',$filters));
        $filters = htmlspecialchars_decode($filters);
        $filters = json_decode($filters, true);
        $ops = htmlspecialchars_decode(iconv('GBK', 'utf-8', $ops));
        $ops = json_decode($ops, true);
        $tableName = '';
        $where = [];
        if ($relationSearch) {
            if (!empty($this->modelClass)) {
                $name = $this->modelClass->getTable();
                $tableName = $name . '.';
            }
            $sortArr = explode(',', $sort);
            foreach ($sortArr as $index => & $item) {
                $item = stripos($item, ".") === false ? $tableName . trim($item) . ' ' . $order : $item . ' ' . $order;
            }
            unset($item);
            $sort = implode(',', $sortArr);
        } else {
            $sort = ["$sort" => $order];
        }
        foreach ($filters as $key => $val) {
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
            $key = stripos($key, ".") === false ? $tableName . $key : $key;
            switch (strtoupper($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'BETWEEN':
                    $arr = array_slice(explode(',', $val), 0, 2);
                    if (stripos($val, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
//                    [$begin, $end] = [$arr[0], $arr[1]];
                    if ($arr[0]) {
                        $where[] = [$key, '>=', ($arr[0])];
                    }
                    if ($arr[1]) {
                        $where[] = [$key, '<=', ($arr[1])];
                    }
                    break;
                case 'IN':
                    $arr = explode(',', $val);
                    $where[] = [$key, 'in', $arr];
                    break;
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $val), 0, 2);
                    if (stripos($val, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
//                    [$begin, $end] = [$arr[0], $arr[1]];
                    if ($arr[0]) {
                        $where[] = [$key, '<=', ($arr[0])];
                    }
                    if ($arr[1]) {
                        $where[] = [$key, '>=', ($arr[1])];
                    }
                    break;
                case 'RANGE':
                    $val = str_replace(' - ', ',', $val);
                    $arr = array_slice(explode(',', $val), 0, 2);
                    if (stripos($val, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
//                    [$begin, $end] = [$arr[0], $arr[1]];
                    if ($arr[0]) {
                        $where[] = [$key, '>=', $arr[0]];
                    }
                    if ($arr[1]) {
                        $where[] = [$key, '<=', $arr[1]];
                    }
                    break;
                case 'NOT RANGE':
                    $val = str_replace(' - ', ',', $val);
                    $arr = array_slice(explode(',', $val), 0, 2);
                    if (stripos($val, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
//                    [$begin, $end] = [$arr[0], $arr[1]];
                    //当出现一边为空时改变操作符
                    if ($arr[0] !== '') {
                        $where[] = [$key, '<=', $arr[0]];
                    } elseif ($arr[1] === '') {
                        $where[] = [$key, '>=', $arr[1]];
                    }
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$key, strtolower(str_replace('IS ', '', $op))];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}%"];
            }
        }
        if ($withStatus) $where[] = [$tableName . 'status', '=', 1];
        return [$page, $limit, $sort, $where];
    }

    //删除

    public function returnDataJson($data = [], $count = 0)
    {
        $result = [
            'code' => 200,
            'msg' => '请求成功',
            'count' => $count,
            'time' => \think\facade\Request::instance()->server('REQUEST_TIME'),
            'data' => $data,
        ];
        LogService::addLog($data);
        return json($result);
    }

    public function view($id)
    {
        $list = $this->modelClass
            ->where(['id' => $id])
            ->find();
        return $this->returnDataJson($list, $list ? 1 : 0);
    }

    public function delete($id)
    {
        $model = $this->modelClass;
        $model::deleteAll(['id' => $id]);
        return $this->returnDataJson();
    }
}
