<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 审核通知管理
 * @group MOBILE
 */
class Inform extends Common {

    /**
     * @title 未读通知数量获取接口
     * @url /inform/unreadnum
     * @method get
     * @return total:未读消息总数(大于0有未读,0没有)
     * @author 开发者
     */
    public function unreadnum() {
        $m     = new \app\mobile\model\Inform();
        $total = $m->unreadnum();
        if ($total === false) {
            show_json(0, $m->getError());
        }
        show_json(1, array('data' => $total));
    }

    /**
     * @title 列表
     * @url /Inform
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
     * @title 编辑
     * @url /company/:id
     * @method put
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:name type:string require:1 default:- other:- desc:法人姓名
     * @param name:area type:string require:1 default:- other:- desc:公司地址省市区
     * @param name:address type:string require:1 default:- other:- desc:公司详细地址
     * @param name:number type:int require:1 default:- other:- desc:电梯数量
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:image type:string require:1 default:- other:- desc:营业执照
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Company();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /company/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return company_name:公司名称
     * @return phone:联系电话
     * @return name:法人姓名
     * @return area:公司地址省市区
     * @return address:公司详细地址
     * @return number:电梯数量
     * @return brand:电梯品牌
     * @return image:营业执照
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Company();
        $m->GetOne($id);
    }

}