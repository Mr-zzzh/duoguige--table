<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 订单管理
 * @group MOBILE
 */
class GoodsOrder extends Common {

    /**
     * @title 我的订单
     * @url /goodsorder
     * @method get
     * @param name:status type:int require:0 default:- other:- desc:状态_1待发货_2已发货_3已收货
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id ordersn:订单号 number:商品数量 money:订单金额 status:-1取消订单_0待支付_1支付_2已发货_3已收货 status_text:状态文本 paytime:支付时间 name:商品名 thumbnail:商品图 price:商品价格 label:商品标签
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\GoodsOrder();
        $m->GetAll(request()->get());
    }

    /**
     * @title 发货提醒/确认收货
     * @url /goodsorder
     * @method post
     * @param name:id type:int require:1 default:- other:- desc:订单id
     * @param name:type type:int require:1 default:- other:- desc:类型_1发货提醒_2确认收货
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\GoodsOrder();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /goodsorder/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\GoodsOrder();
        $m->DelOne($id);
    }

    /**
     * @title 订单确认页
     * @url /goodsorder/affirm
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:商品id
     * @return shop:商品信息@
     * @shop id:id name:商品名 thumbnail:商品图 price:单价 label:商品标签 number:商品数量
     * @return address:地址信息@(为空没有默认地址)
     * @address id:id uid:用户id name:收货人姓名 phone:收货人电话 area:-收货地区 address:收货详细地址 createtime:创建时间
     * @author 开发者
     */
    public function affirm() {
        $m = new \app\mobile\model\GoodsOrder();
        $m->Affirm(request()->param());
    }

    /**
     * @title 订单支付
     * @url /goodsorder/pay
     * @method post
     * @param name:id type:int require:1 default:- other:- desc:商品id
     * @param name:addressid type:int require:1 default:- other:- desc:地址id
     * @param name:paytype type:int require:1 default:- other:- desc:支付方式_1支付宝_2微信
     * @return data:支付信息@
     * @data
     * @author 开发者(返回status为2时,不调用支付,金额为0)
     */
    public function pay() {
        $m = new \app\mobile\model\GoodsOrder();
        $m->Pay(request()->param());
    }

    public function notify($data = []) {
        $m = new \app\mobile\model\GoodsOrder();
        $m->Notify($data);
    }

    /**
     * @title 读取
     * @url /goodsorder/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return gid:商品id
     * @return ordersn:订单号
     * @return number:商品数量
     * @return money:商品金额
     * @return status:-1取消订单_0待支付_1支付_2已发货_3已收货_4退款
     * @return paytype:1支付宝_2微信
     * @return tradeno:交易单号
     * @return addressid:地址id
     * @return freight:运费
     * @return expresscom:快递公司
     * @return expresssn:快递单号
     * @return paytime:支付时间
     * @return finishtime:完成时间
     * @return canceltime:取消时间
     * @return createtime:创建时间
     * @return delivertime:发货时间
     * @author 开发者
     */
    /*public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\GoodsOrder();
        $m->GetOne($id);
    }*/

}