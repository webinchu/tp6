<?php
declare(strict_types=1);

/**
 * Created by phpStorm.
 * User: webin
 * Date: 2021/5/28
 * Time: 11:36
 */


namespace app\common\job;


class CommonJob
{
    /**
     * @inheritDoc
     */
    public static function execute(string $callback, array $params)
    {
        return call_user_func_array($callback, $params);
    }
}
