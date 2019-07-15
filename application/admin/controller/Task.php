<?php

namespace app\admin\controller;

use think\Controller;

class Task extends Controller {
    public function order() {
        /* if (!IS_CLI) {
             return '请在cli模式下运行';
         }*/
        chmod(RUNTIME_PATH . 'log/' . date('Ym'), 0777);
        ignore_user_abort(); //忽略关闭浏览器
        set_time_limit(0); //永远执行
        $time = time() - 60 * 60 * 24 * 7;
        db('goods_order')->where(array('status' => 2, 'delivertime' => array('<', $time)))->update(array('status' => 3, 'finishtime' => time()));
        $time1 = time() - 60 * 60 * 24 * 3;
        db('maintenance')->where(array('status' => 3, 'complete_time' => array('<', $time1)))->update(array('status' => 4, 'finishtime' => time()));
        echo '订单或者维保单自动完成:' . date('Y-m-d H:i:s', time()) . PHP_EOL;
        exit();
    }

}