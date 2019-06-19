<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 招聘管理
 * @group MOBILE
 */
class Invite extends Common {

    /**
     * @title 期望薪资列表
     * @url /salary
     * @method get
     * @return data:列表@
     * @data id:id name:薪资内容 createtime:创建时间
     * @author 开发者
     */
    public function salary() {
        $m = new \app\mobile\model\Invite();
        $m->Salary();
    }

    /**
     * @title 工作经验列表
     * @url /experience
     * @method get
     * @return data:列表@
     * @data id:id name:经验内容 createtime:创建时间
     * @author 开发者
     */
    public function experience() {
        $m = new \app\mobile\model\Invite();
        $m->Experience();
    }

    /**
     * @title 列表
     * @url /invite
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:salary type:int require:0 default:- other:- desc:薪资范围
     * @param name:province type:int require:0 default:- other:- desc:省编号
     * @param name:city type:int require:0 default:- other:- desc:市编号
     * @param name:area type:int require:0 default:- other:- desc:区编号
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id post:招聘岗位 salary_text:工资范围 province:省编号 province_text:省 city:市编号 city_text:市 name:联系人姓名 phone:联系电话 company_name:公司名称 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Invite();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /invite
     * @method post
     * @param name:post type:string require:1 default:- other:- desc:招聘岗位
     * @param name:education type:string require:1 default:- other:- desc:学历(不限/初中/中专/高中大专/本科/硕士/博士)
     * @param name:salary type:int require:1 default:- other:- desc:工资范围id
     * @param name:experience type:int require:1 default:- other:- desc:工作经验id
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:area type:int require:1 default:- other:- desc:区编号
     * @param name:address type:string require:1 default:- other:- desc:详细地址
     * @param name:description type:string require:1 default:- other:- desc:岗位描述
     * @param name:duty type:string require:1 default:- other:- desc:岗位职责
     * @param name:name type:string require:1 default:- other:- desc:联系人姓名
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:number type:string require:1 default:- other:- desc:招聘人数(不限或者数字)
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Invite();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /invite/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Invite();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /invite/:id
     * @method put
     * @param name:post type:string require:1 default:- other:- desc:招聘岗位
     * @param name:education type:string require:1 default:- other:- desc:学历(不限/初中/中专/高中大专/本科/硕士/博士)
     * @param name:salary type:int require:1 default:- other:- desc:工资范围id
     * @param name:experience type:int require:1 default:- other:- desc:工作经验id
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:area type:int require:1 default:- other:- desc:区编号
     * @param name:address type:string require:1 default:- other:- desc:详细地址
     * @param name:description type:string require:1 default:- other:- desc:岗位描述
     * @param name:duty type:string require:1 default:- other:- desc:岗位职责
     * @param name:name type:string require:1 default:- other:- desc:联系人姓名
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:number type:string require:1 default:- other:- desc:招聘人数(不限或者数字)
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Invite();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /invite/:id
     * @method get
     * @return id:id
     * @return uid:发布招聘用户id
     * @return post:招聘岗位
     * @return salary:工资范围
     * @return experience:工作经验
     * @return province:省编号
     * @return city:市编号
     * @return description:岗位描述
     * @return duty:岗位职责
     * @return name:联系人姓名
     * @return phone:联系电话
     * @return status:状态_0待审_1通过_2不通过_3招聘结束
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Invite();
        $m->GetOne($id);
    }

}