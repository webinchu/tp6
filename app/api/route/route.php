<?php

use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('user/index', 'user/index');
Route::get('test-queue', 'user/testQueue');
Route::get('doc', 'doc/index');
