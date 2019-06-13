<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title Company
 * @group MOBILE
 */
class Company extends Common {

    /**
     * @title 列表
     * @url /company
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id company_name:公司名称 phone:联系电话 name:法人姓名 area:公司地址省市区 address:公司详细地址 number:电梯数量 brand:电梯品牌 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Company();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /company/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /company
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:name type:string require:1 default:- other:- desc:法人姓名
     * @param name:area type:string require:1 default:- other:- desc:公司地址省市区
     * @param name:address type:string require:1 default:- other:- desc:公司详细地址
     * @param name:number type:int require:1 default:- other:- desc:电梯数量
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Company();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /company/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Company();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /company/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:name type:string require:1 default:- other:- desc:法人姓名
     * @param name:area type:string require:1 default:- other:- desc:公司地址省市区
     * @param name:address type:string require:1 default:- other:- desc:公司详细地址
     * @param name:number type:int require:1 default:- other:- desc:电梯数量
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
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