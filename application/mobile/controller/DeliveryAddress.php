<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title DeliveryAddress
 * @group MOBILE
 */
class DeliveryAddress extends Common {

    /**
     * @title 列表
     * @url /deliveryaddress
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
     * @data id:id uid:用户ID name:收货人姓名 phone:收货人电话 address:地址 default:是否默认_0否_1是 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\DeliveryAddress();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /deliveryaddress/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /deliveryaddress
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户ID
     * @param name:name type:string require:1 default:- other:- desc:收货人姓名
     * @param name:phone type:string require:1 default:- other:- desc:收货人电话
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
     * @param name:uid type:int require:1 default:- other:- desc:用户ID
     * @param name:name type:string require:1 default:- other:- desc:收货人姓名
     * @param name:phone type:string require:1 default:- other:- desc:收货人电话
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