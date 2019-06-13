<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title Admin
 * @group MOBILE
 */
class Admin extends Common {

    /**
     * @title 列表
     * @url /admin
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:状态：0-禁用，1-启用
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id roleid:角色id name:名称(账号) password:密码 salt:随机盐 status:状态：0-禁用，1-启用 token:用户token createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Admin();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /admin/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /admin
     * @method post
     * @param name:roleid type:int require:1 default:- other:- desc:角色id
     * @param name:name type:string require:1 default:- other:- desc:名称(账号)
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:salt type:string require:1 default:- other:- desc:随机盐
     * @param name:status type:int require:1 default:- other:- desc:状态：0-禁用，1-启用
     * @param name:token type:string require:1 default:- other:- desc:用户token
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Admin();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Admin();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/:id
     * @method put
     * @param name:roleid type:int require:1 default:- other:- desc:角色id
     * @param name:name type:string require:1 default:- other:- desc:名称(账号)
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:salt type:string require:1 default:- other:- desc:随机盐
     * @param name:status type:int require:1 default:- other:- desc:状态：0-禁用，1-启用
     * @param name:token type:string require:1 default:- other:- desc:用户token
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Admin();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/:id
     * @method get
     * @return id:id
     * @return roleid:角色id
     * @return name:名称(账号)
     * @return password:密码
     * @return salt:随机盐
     * @return status:状态：0-禁用，1-启用
     * @return token:用户token
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Admin();
        $m->GetOne($id);
    }

}