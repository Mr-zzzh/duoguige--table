<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 角色管理
 * @group ADMIN
 */
class Role extends Common {

    /**
     * @title 角色列表
     * @url /admin/role
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @return data:列表@
     * @data id:id name:角色名称 rule:权限 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Role();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /admin/role/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /admin/role
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:角色名称
     * @param name:rule type:string require:1 default:- other:- desc:权限
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Role();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/role/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Role();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/role/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:角色名称
     * @param name:rule type:string require:1 default:- other:- desc:权限
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Role();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/role/:id
     * @method get
     * @return id:id
     * @return name:角色名称
     * @return rule:权限
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Role();
        $m->GetOne($id);
    }

}