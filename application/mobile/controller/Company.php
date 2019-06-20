<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 企业认证管理
 * @group MOBILE
 */
class Company extends Common {

    /**
     * @title 认证信息提交
     * @url /company
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:phone type:string require:1 default:- other:- desc:联系电话
     * @param name:name type:string require:1 default:- other:- desc:法人姓名
     * @param name:area type:string require:1 default:- other:- desc:公司地址省市区(湖北省武汉市武昌区)
     * @param name:address type:string require:1 default:- other:- desc:公司详细地址
     * @param name:number type:int require:1 default:- other:- desc:电梯数量
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:image type:string require:1 default:- other:- desc:营业执照
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Company();
        $m->AddOne(request()->post());
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