<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 订单管理
 * @group ADMIN
 */
class GoodsOrder extends Common {

    /**
     * @title 列表
     * @url /admin/goodsorder
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:-1取消订单_0待支付_1已支付_2已发货_3已收货
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id uname:用户姓名 gid:商品id gname:商品名 thumbnail:商品缩略图 ordersn:订单号 number:商品数量 money:商品金额 status:-1取消订单_0待支付_1支付_2已发货_3已收货 status_text:状态文本 paytype:1支付宝_2微信 paytype_text:付款方式文本 tradeno:交易单号 addressid:地址id paytime:支付时间 finishtime:完成时间 canceltime:取消时间 createtime:创建时间 delivertime:发货时间 dname:收货人姓名 phone:收货人联系方式 area:地区 address:收货地址
     * @return number:订单数
     * @return money:订单金额
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\GoodsOrder();
        $m->GetAll(request()->get());
    }

    /**
     * @title 发货
     * @url /admin/goodsorder/deliver
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:订单id
     * @author 开发者
     */
    public function deliver() {
        $m = new \app\admin\model\GoodsOrder();
        $m->Deliver(request()->param());
    }

    /**
     * @title 订单走势图
     * @url /admin/goodsorder/trend_chart
     * @method get
     * @param name:starttime type:string require:1 default:- other:- desc:开始时间(年-月-日)_当天传一样的
     * @param name:endtime type:string require:1 default:- other:- desc:结束时间(年-月-日)
     * @return legend:标题@
     * @return series:数据@
     * @return time:时间数组@
     * @series name:名称 type:line stack:总量 data:数据
     * @author 开发者 (echarst图 https://www.echartsjs.com/examples/editor.html?c=line-stack)
     */
    /*public function trend_chart() {
        $m = new \app\admin\model\GoodsOrder();
        $m->TrendChart(request()->param());
    }*/

    /**
     * @title 订单概述
     * @url /admin/goodsorder/summarize
     * @method get
     * @return data:列表@(today_今日_yesterday昨日_seven近7日_month近30日)
     * @data turnover:成交量 volume:交易量 number:成交额 number1:交易额 average:人均消费 trend:近30日交易走势(https://www.echartsjs.com/examples/editor.html?c=line-stack)
     * @author 开发者
     */
    public function summarize() {
        $m = new \app\admin\model\GoodsOrder();
        $m->Summarize();
    }

    /**
     * @title 删除
     * @url /admin/goodsorder/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\GoodsOrder();
        $m->DelOne($id);
    }

    /**
     * @title 读取
     * @url /admin/goodsorder/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return uname:用户姓名
     * @return gid:商品id
     * @return gname:商品名
     * @return thumbnail:商品缩略图
     * @return ordersn:订单号
     * @return number:商品数量
     * @return money:商品金额
     * @return status:-1取消订单_0待支付_1支付_2已发货_3已收货
     * @return status_text:状态文本
     * @return paytype:1支付宝_2微信
     * @return paytype_text:付款方式文本
     * @return tradeno:交易单号
     * @return addressid:地址id
     * @return paytime:支付时间
     * @return finishtime:完成时间
     * @return canceltime:取消时间
     * @return createtime:创建时间
     * @return delivertime:发货时间
     * @return dname:收货人姓名
     * @return phone:收货人联系方式
     * @return area:地区
     * @return address:收货地址
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\GoodsOrder();
        $m->GetOne($id);
    }

}