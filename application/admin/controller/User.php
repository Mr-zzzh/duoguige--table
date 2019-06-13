<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 用户列表
 * @group ADMIN
 */
class User extends Common {

    /**
     * @title 用户列表
     * @url /admin/user
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:审核状态_0待审_1通过_2不通过
     * @param name:type type:int require:0 default:- other:- desc:用户类型_1,普通用户,2技术大师,3物业公司
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:姓名 phone:电话 avatar:头像 password:密码 salt:随机加盐 intro:简介 status:审核状态_0待审_1通过_2不通过 type:用户类型_1,普通用户,2技术大师,3物业公司 token:用户标识 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\User();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /admin/user/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /admin/user
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:姓名
     * @param name:phone type:string require:1 default:- other:- desc:电话
     * @param name:avatar type:string require:1 default:- other:- desc:头像
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:salt type:string require:1 default:- other:- desc:随机加盐
     * @param name:intro type:string require:1 default:- other:- desc:简介
     * @param name:status type:int require:1 default:- other:- desc:审核状态_0待审_1通过_2不通过
     * @param name:type type:int require:1 default:- other:- desc:用户类型_1,普通用户,2技术大师,3物业公司
     * @param name:token type:string require:1 default:- other:- desc:用户标识
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\User();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/user/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\User();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/user/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:姓名
     * @param name:phone type:string require:1 default:- other:- desc:电话
     * @param name:avatar type:string require:1 default:- other:- desc:头像
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:salt type:string require:1 default:- other:- desc:随机加盐
     * @param name:intro type:string require:1 default:- other:- desc:简介
     * @param name:status type:int require:1 default:- other:- desc:审核状态_0待审_1通过_2不通过
     * @param name:type type:int require:1 default:- other:- desc:用户类型_1,普通用户,2技术大师,3物业公司
     * @param name:token type:string require:1 default:- other:- desc:用户标识
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\User();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/user/:id
     * @method get
     * @return id:id
     * @return name:姓名
     * @return phone:电话
     * @return avatar:头像
     * @return password:密码
     * @return salt:随机加盐
     * @return intro:简介
     * @return status:审核状态_0待审_1通过_2不通过
     * @return type:用户类型_1,普通用户,2技术大师,3物业公司
     * @return token:用户标识
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\User();
        $m->GetOne($id);
    }

}