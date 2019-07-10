<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 省市区
 * @group MOBILE
 */
class Area extends Common {

    /**
     * @title 省市区结构树
     * @url /area
     * @method get
     * @return id:父级数据@!
     * @id id:ID name:名称 pid:上级id rank:评级 code:编码 _child:子级数据@!
     * @_child _id:子级ID@!
     * @_id id:ID name:名称 pid:上级id rank:评级 _child:子级数据(同上)
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Area();
        $m->GetAll();
    }

}