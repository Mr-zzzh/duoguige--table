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
     * @title 编辑
     * @url /goodsorder/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:gid type:int require:1 default:- other:- desc:商品id
     * @param name:ordersn type:string require:1 default:- other:- desc:订单号
     * @param name:number type:int require:1 default:- other:- desc:商品数量
     * @param name:money type:float require:1 default:- other:- desc:商品金额
     * @param name:status type:int require:1 default:- other:- desc:-1取消订单_0待支付_1支付_2已发货_3已收货_4退款
     * @param name:paytype type:int require:1 default:- other:- desc:1支付宝_2微信
     * @param name:tradeno type:int require:1 default:- other:- desc:交易单号
     * @param name:addressid type:int require:1 default:- other:- desc:地址id
     * @param name:freight type:float require:1 default:- other:- desc:运费
     * @param name:expresscom type:string require:1 default:- other:- desc:快递公司
     * @param name:expresssn type:string require:1 default:- other:- desc:快递单号
     * @param name:paytime type:int require:1 default:- other:- desc:支付时间
     * @param name:finishtime type:int require:1 default:- other:- desc:完成时间
     * @param name:canceltime type:int require:1 default:- other:- desc:取消时间
     * @param name:delivertime type:int require:1 default:- other:- desc:发货时间
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\GoodsOrder();
        $m->EditOne($request->put(), $id);
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
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\GoodsOrder();
        $m->GetOne($id);
    }

}