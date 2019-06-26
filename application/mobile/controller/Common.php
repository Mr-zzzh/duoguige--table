<?php

namespace app\mobile\controller;

use think\Controller;

class Common extends Controller {

    public function _initialize() {
        parent::_initialize();
        global $member;
        set_exception_handler(function ($e) {
            show_json(-1, $e->getMessage() ?: '系统出现异常');
        });
        header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,token');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        $module     = request()->module();
        $controller = request()->controller();
        $action     = request()->action();
        $path       = strtolower($module . '/' . $controller . '/' . $action);
        //用户登录验证
        $member = mobile_login();
        if ($member === false) {
            if (!in_array($path, login_comc())) {
                show_json(-3, '用户未登录!');
            }
        }
    }

    public
    function miss() {
        show_json(-2, '路由未定义!');
    }

}