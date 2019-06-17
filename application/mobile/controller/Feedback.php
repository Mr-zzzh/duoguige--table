<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 意见反馈
 * @group MOBILE
 */
class Feedback extends Common {
    /**
     * @title 添加
     * @url /feedback
     * @method post
     * @param name:content type:string require:1 default:- other:- desc:反馈内容
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Feedback();
        $m->AddOne(request()->post());
    }

}