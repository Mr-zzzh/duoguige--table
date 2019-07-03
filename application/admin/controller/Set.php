<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 设置管理
 * @group ADMIN
 */
class Set extends Common {

    /**
     * @title 列表
     * @url /admin/set
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
        $m = new \app\admin\model\Set();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加启屏页
     * @url /admin/set
     * @method post
     * @param name:slogan type:string require:1 default:- other:- desc:口号
     * @param name:number type:int require:1 default:- other:- desc:编号
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Set();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/set/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Set();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/set/:id
     * @method put
     * @param name:content type:string require:1 default:- other:- desc:内容
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Set();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/set/:id
     * @method get
     * @return id:id
     * @return content:内容
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Set();
        $m->GetOne($id);
    }

}