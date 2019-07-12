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
     * @data id:id name:姓名 phone:电话 avatar:头像 intro:简介 status:审核状态_0待审_1通过_2不通过 status_text:状态文本 type:用户类型_1普通用户_2技术大师_3物业公司 type_text:类型文本 createtime:创建时间 normal:是否启用_1启用_2禁用 normal_text:是否启用 number:订单数 money:金额
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\User();
        $m->GetAll(request()->get());
    }

    /**
     * @title 大师管理
     * @url /admin/user/technician
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码+
     * @return data:列表@
     * @data id:id name:姓名 phone:电话 number:订单数 score:分数 grade:等级
     * @author 开发者
     */
    public function technician() {
        $m = new \app\admin\model\User();
        $m->Technician(request()->get());
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
    /*  public function save() {
          $m = new \app\admin\model\User();
          $m->AddOne(request()->post());
      }*/

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
    /*public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\User();
        $m->EditOne($request->put(), $id);
    }*/

    /**
     * @title 读取
     * @url /admin/user/:id
     * @method get
     * @return id:id
     * @return name:姓名
     * @return phone:电话
     * @return avatar:头像
     * @return intro:简介
     * @return status:审核状态_0待审_1通过_2不通过
     * @return status_text:状态文本
     * @return type:用户类型_1普通用户_2技术大师_3物业公司
     * @return type_text:类型文本
     * @return normal:是否启用_1启用_2禁用
     * @return normal_text:是否启用
     * @return createtime:创建时间
     * @return check:认证信息数组(check1或者check2)
     * @return check1:认证信息@(技术大师)
     * @return check2:认证信息@(物业公司)
     * @check1 id:id uid:用户id name:真实姓名 sex:性别1男2女 idcardno:身份证号码 company_name:公司名称 license_number:公司营业执照号码 company_image:公司营业执照照片 prove_image:在职证明图片 technician_image:技师证件 dimission:离职证明图 createtime:创建时间 remark:审核备注
     * @check2 id:id uid:用户id company_name:公司名称 phone:联系电话 name:法人姓名 area:公司地址省市区 address:公司详细地址 number:电梯数量 brand:电梯品牌 image:营业执照 createtime:创建时间 remark:审核备注
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\User();
        $m->GetOne($id);
    }

    /**
     * @title 审核
     * @url /admin/user/editstatus
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:用户id
     * @param name:status type:int require:1 default:- other:- desc:状态_1通过_2不通过
     * @param name:remark type:string require:0 default:- other:- desc:审核备注
     * @author 开发者
     */
    public function editstatus() {
        $m = new \app\admin\model\User();
        $m->EditStatus(request()->param());
    }

    /**
     * @title 禁用/启用
     * @url /admin/user/forbidden
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:用户id
     * @param name:normal type:int require:1 default:- other:- desc:是否启用_1启用_2禁用
     * @author 开发者
     */
    public function forbidden() {
        $m = new \app\admin\model\User();
        $m->Forbidden(request()->param());
    }
}