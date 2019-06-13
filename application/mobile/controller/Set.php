<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title Set
 * @group MOBILE
 */
class Set extends Common {

    /**
     * @title 列表
     * @url /set
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id content:内容
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Set();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /set/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /set
     * @method post
     * @param name:content type:string require:1 default:- other:- desc:内容
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Set();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /set/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Set();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /set/:id
     * @method put
     * @param name:content type:string require:1 default:- other:- desc:内容
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Set();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /set/:id
     * @method get
     * @return id:id
     * @return content:内容
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Set();
        $m->GetOne($id);
    }

}