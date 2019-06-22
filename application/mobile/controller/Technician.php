<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 大师管理
 * @group MOBILE
 */
class Technician extends Common {

    /**
     * @title 大师列表
     * @url /technician
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:用户id name:真实姓名 avatar:头像 phone:手机号 label:已认证维修大师
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Technician();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /technician
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:name type:string require:1 default:- other:- desc:真实姓名
     * @param name:sex type:int require:1 default:- other:- desc:性别1男2女
     * @param name:idcardno type:string require:1 default:- other:- desc:身份证号码
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:license_number type:string require:1 default:- other:- desc:公司营业执照号码
     * @param name:company_image type:string require:1 default:- other:- desc:公司营业执照照片
     * @param name:prove_image type:string require:1 default:- other:- desc:在职证明图片
     * @param name:technician_image type:string require:1 default:- other:- desc:技师证件
     * @param name:dimission type:string require:1 default:- other:- desc:离职证明图
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Technician();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /technician/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Technician();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /technician/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:name type:string require:1 default:- other:- desc:真实姓名
     * @param name:sex type:int require:1 default:- other:- desc:性别1男2女
     * @param name:idcardno type:string require:1 default:- other:- desc:身份证号码
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:license_number type:string require:1 default:- other:- desc:公司营业执照号码
     * @param name:company_image type:string require:1 default:- other:- desc:公司营业执照照片
     * @param name:prove_image type:string require:1 default:- other:- desc:在职证明图片
     * @param name:technician_image type:string require:1 default:- other:- desc:技师证件
     * @param name:dimission type:string require:1 default:- other:- desc:离职证明图
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Technician();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /technician/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return name:真实姓名
     * @return sex:性别1男2女
     * @return idcardno:身份证号码
     * @return company_name:公司名称
     * @return license_number:公司营业执照号码
     * @return company_image:公司营业执照照片
     * @return prove_image:在职证明图片
     * @return technician_image:技师证件
     * @return dimission:离职证明图
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Technician();
        $m->GetOne($id);
    }

}