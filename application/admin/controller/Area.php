<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 省市区
 * @group ADMIN
 */
class Area extends Common {

    /**
     * @title 列表
     * @url /admin/area
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:ID name:名称 level:级别 pid:上级id code:编码
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Area();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /admin/area/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /admin/area
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:名称
     * @param name:level type:int require:1 default:- other:- desc:级别
     * @param name:pid type:int require:1 default:- other:- desc:上级id
     * @param name:code type:int require:1 default:- other:- desc:编码
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Area();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/area/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Area();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/area/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:名称
     * @param name:level type:int require:1 default:- other:- desc:级别
     * @param name:pid type:int require:1 default:- other:- desc:上级id
     * @param name:code type:int require:1 default:- other:- desc:编码
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Area();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/area/:id
     * @method get
     * @return id:ID
     * @return name:名称
     * @return level:级别
     * @return pid:上级id
     * @return code:编码
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Area();
        $m->GetOne($id);
    }

}