<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 收货地址管理
 * @group MOBILE
 */
class DeliveryAddress extends Common {

    /**
     * @title 列表
     * @url /deliveryaddress
     * @method get
     * @return data:列表@
     * @data id:id uid:用户ID name:收货人姓名 phone:收货人电话 area:地区 address:地址 default:是否默认_0否_1是 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\DeliveryAddress();
        $m->GetAll();
    }

    /**
     * @title 添加
     * @url /deliveryaddress
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:收货人姓名
     * @param name:phone type:string require:1 default:- other:- desc:收货人电话
     * @param name:area type:string require:1 default:- other:- desc:地区
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:default type:int require:1 default:- other:- desc:是否默认_0否_1是
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\DeliveryAddress();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /deliveryaddress/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\DeliveryAddress();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /deliveryaddress/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:收货人姓名
     * @param name:phone type:string require:1 default:- other:- desc:收货人电话
     * @param name:area type:string require:1 default:- other:- desc:地区
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:default type:int require:1 default:- other:- desc:是否默认_0否_1是
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\DeliveryAddress();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /deliveryaddress/:id
     * @method get
     * @return id:id
     * @return uid:用户ID
     * @return name:收货人姓名
     * @return phone:收货人电话
     * @return area:地区
     * @return address:地址
     * @return default:是否默认_0否_1是
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\DeliveryAddress();
        $m->GetOne($id);
    }

}