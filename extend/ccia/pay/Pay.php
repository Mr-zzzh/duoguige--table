<?php

namespace ccia\pay;

use app\mobile\controller\Order;
use app\mobile\controller\GoodsOrder;
use think\Db;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Pay extends Common {
    //订单支付调用 $orderinfo = array('body'=>'支付描述','title'=>'支付标题','ordersn'=>'支付订单号','money'=>'支付金额','timeout_express'=>'过期时间(90m--90分钟)','notify'=>'回调通知地址(公共方法中可设置默认)','return_url'=>'回跳地址(公共方法中可设置默认)','openid'=>'微信公众号支付与小程序支付时需要参数','code'=>'小程序支付若不传openid则需要code','client'=>'支付端(默认值'web',非web则表示app端)','paytype'=>'选择支付方式:1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付')  |   return array('status'=>(0-失败,1-成功),'message'=>'提示信息','result'=>'支付创建成功时返回,交由前端处理')
    public function pay($orderinfo) {
        if (empty($orderinfo)) {
            return ccia_return(0, '请选择支付订单');
        }
        if (!in_array($orderinfo['paytype'], $this->paytype)) {
            return ccia_return(0, '支付方式错误');
        }
        $GLOBALS['ccia_pay_type'] = $orderinfo['paytype'];
        if (!empty($orderinfo['notify']) && $orderinfo['paytype'] == 1) {
            $this->alinotify = $orderinfo['notify'];
        } elseif (!empty($orderinfo['notify']) && $orderinfo['paytype'] != 1) {
            $this->wxnotify = $orderinfo['notify'];
        }
        if (!empty($orderinfo['client'])) {
            $this->client = trim($orderinfo['client']);
        }
        if (!empty($orderinfo['return_url'])) {
            $this->return_url = trim($orderinfo['return_url']);
        }
        $data   = array(
            'ordersn'    => $orderinfo['ordersn'],
            'ordermoney' => $orderinfo['money'],
            'paytype'    => $orderinfo['paytype']
        );
        $paylog = new Paylog();
        $res    = $paylog->add($data);
        if ($res['status'] == 0) {
            return $res;
        }
        $paying = new Paying();
        if ($orderinfo['paytype'] == 1) {
            //支付宝支付
            if ($this->client != 'web') {
                //app支付
                $res = $paying->Aliapppay($orderinfo);
            } else {
                //H5支付
                if (!isMobile()) {
                    //pc端支付
                    $res = $paying->Aliwebpay($orderinfo);
                } else {
                    //mobile端支付
                    $res = $paying->Alimobilepay($orderinfo);
                }
            }
        } elseif ($orderinfo['paytype'] == 2) {
            //微信app支付
            $res = $paying->Wxapppay($orderinfo);
        } elseif ($orderinfo['paytype'] == 3) {
            //微信H5公众号支付
            if (is_weixin()) {
                //公众号支付
                $res = $paying->Wxjsapipay($orderinfo);
            } else {
                if (!isMobile()) {
                    //pc端支付
                    $res = $paying->Wxh5pcpay($orderinfo);
                } else {
                    //mobile端支付
                    $res = $paying->Wxh5mobilepay($orderinfo);
                }
            }
        } elseif ($orderinfo['paytype'] == 4) {
            //微信小程序支付
            $res = $paying->Wxminipay($orderinfo);
        }
        if ($res['status'] == 1) {
            $res['message'] = $orderinfo['ordersn'];
        }
        return $res;
    }

    //订单支付状态主动查询,返回status=0(可再次查询)-1(支付失败)1(支付成功)
    public function query($ordersn = '') {
        if (empty($ordersn)) {
            return ccia_return(-1, '请选择订单');
        }
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn' => $ordersn))->field('id,ordersn,paystatus,paytype')->find();
        if (!in_array($orderinfo['paytype'], $this->paytype)) {
            return ccia_return(-1, '订单支付方式错误');
        }
        if ($orderinfo['paystatus'] == 1) {
            return ccia_return(1, '订单已支付');
        }
        if ($orderinfo['paystatus'] == -1) {
            return ccia_return(-1, '订单已关闭');
        }
        if ($orderinfo['paystatus'] == -2) {
            return ccia_return(-1, '订单已退款');
        }
        $payed = new Payed();
        $res   = $payed->order_query($orderinfo);
        if ($res['status'] == 0) {
            //订单查询失败
            return $res;
        } elseif ($res['status'] == 1) {
            //订单支付成功
            $data = array(
                'paytime'   => $res['result']['paytime'],
                'paystatus' => 1,
                'trade_no'  => $res['result']['trade_no'],
                'paymoney'  => $res['result']['paymoney'],
                'ordersn'   => $ordersn
            );
            $res  = $this->paynotify($data);
            if ($res['status'] == 0) {
                $res['status'] = '-1';
            }
            return $res;
        }
        return ccia_return(-1, '查询订单异常');
    }

    //支付订单退款 ['refundsn'=>'退款订单号,为空时自动生成','ordersn'=>'需要退款的订单号','refundmoney'=>'退款金额','reason'=>'退款备注']
    public function refund($refund) {
        if (is_array($refund)) {
            $refundinfo = $refund;
        } else {
            $refundinfo['ordersn'] = $refund;
        }
        if (empty($refundinfo['ordersn'])) {
            return ccia_return(0, '请选择订单');
        }
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn' => $refundinfo['ordersn']))->field('id,ordersn,paystatus,paymoney,paytype')->find();
        if (empty($orderinfo)) {
            return ccia_return(0, '未找到对应支付记录');
        }
        if (!in_array($orderinfo['paytype'], $this->paytype)) {
            return ccia_return(0, '订单记录支付方式错误');
        }
        $data = array(
            'refundsn'     => $refundinfo['refundsn'],
            'ordersn'      => $refundinfo['ordersn'],
            'ordermoney'   => $orderinfo['paymoney'],
            'refundmoney'  => $refundinfo['refundmoney'] ?: $orderinfo['paymoney'],
            'reason'       => $refundinfo['reason'] ?: '申请退款',
            'refundstatus' => 0,
            'createtime'   => time(),
            'paytype'      => $orderinfo['paytype']
        );
        if (!empty($refundinfo['refundsn'])) {
            $reinfo = Db::table($this->PayRefundTable)->where(array('refundsn' => $orderinfo['refundsn']))->field('id,refundstatus');
            if (!empty($reinfo)) {
                if ($reinfo['refundstatus'] == 1) {
                    return ccia_return(0, '该退款记录已退款');
                } elseif ($reinfo['refundstatus'] == -1) {
                    return ccia_return(0, '该退款记录已关闭');
                }
            }
        }
        if (empty($data['refundsn'])) {
            $data['refundsn'] = createNO($this->PayRefundTable, 'refundsn', 'TK');
        }
        if (!empty($reinfo)) {
            $res = Db::table($this->PayRefundTable)->where(array('id' => $reinfo['id']))->update($data);
        } else {
            $res = Db::table($this->PayRefundTable)->insert($data);
        }
        if ($res === false) {
            return ccia_return(0, '退款记录添加失败');
        }
        $payed = new Payed();
        $res   = $payed->order_refund($data['refundsn']);
        if ($res['status'] == 1) {
            //退款成功
            Db::startTrans();
            $result = Db::table($this->PayRefundTable)->where(array('refundsn' => $data['refundsn']))->update(array('refundstatus' => 1));
            if ($result === false) {
                Db::rollback();
                return ccia_return(0, '修改退款记录状态失败');
            }
            $result = Db::table($this->PayLotTable)->where(array('ordersn' => $data['ordersn']))->update(array('paystatus' => -2));
            if ($result === false) {
                Db::rollback();
                return ccia_return(0, '修改订单记录状态失败');
            }
            Db::commit();
        }
        return $res;
    }

    //回调通知(首次更改支付记录成功状态时(含主动查询支付成功)会进入)--支付成功
    public function notify_success($data) {
        //data:array('ordersn'=>'订单号','paytime'=>'支付时间','paystatus'=>'支付状态1-成功','paymoney'=>'支付金额')
        $paynotify    = new Paynotification();
        $data['sign'] = get_sign($data);
        $paynotify->paysuccess($data);
    }

    //回调通知(首次更改订单失败状态时进入)--支付失败
    public function notify_fail($data) {
        //data:array('ordersn'=>'订单号','paystatus'=>'支付状态-1-关闭','paytime'=>'支付时间(关闭时间)')
        $paynotify    = new Paynotification();
        $data['sign'] = get_sign($data);
        $paynotify->payfail($data);
    }

    //支付取消/关闭
    public function paycancel($ordersn) {
        if (empty($ordersn)) {
            return ccia_return(0, '请选择订单信息');
        }
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn' => $ordersn))->find();
        if (empty($orderinfo)) {
            return ccia_return(0, '未找到对应的订单信息');
        }
        $payed = new Payed();
        $res   = $payed->order_cancel($orderinfo);
        if ($res['status'] == 1) {
            //订单关闭完成
            $result = Db::table($this->PayLotTable)->where(array('ordersn' => $orderinfo['ordersn']))->update(array('paystatus' => '-1'));
            if ($result === false) {
                return ccia_return(0, '订单记录关闭失败');
            }
        }
        return $res;
    }


    //支付回调通知处理
    public function paynotify($orderdata = '') {
        if (empty($orderdata)) {
            return ccia_return(0, '订单信息错误');
        }
        $ordersn   = $orderdata['ordersn'];
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn' => $ordersn))->field('id,paystatus')->find();
        if (empty($orderinfo)) {
            return ccia_return(0, '未找到订单信息');
        }
        if ($orderinfo['paystatus'] >= 1) {
            return ccia_return(1, '订单已支付');
        }
        if ($orderinfo['paystatus'] < 0) {
            return ccia_return(0, '订单已取消或已退款');
        }
        $orderdata['paytime'] = $orderdata['paytime'] ?: time();
        $data                 = array(
            'paytime'   => $orderdata['paytime'],
            'paystatus' => $orderdata['paystatus'],
            'trade_no'  => $orderdata['trade_no'],
            'paytype'   => $orderdata['paytype'],
            'paymoney'  => $orderdata['paymoney']
        );
        if ($data['paystatus'] == 1) {
            //支付成功
            $ordernotify = new GoodsOrder();
            $ordernotify->notify($orderdata);
            $message = '订单支付成功';
        } elseif ($data['paystatus'] == -1) {
            //支付失败
            $ordernotify = new GoodsOrder();
            $ordernotify->notify($orderdata);
            $message = '订单支付失败';
        } else {
            //异常
            $message = '订单异常';
        }
        $res = Db::table($this->PayLotTable)->where(array('ordersn' => $ordersn))->update($data);
        if ($res === false) {
            return ccia_return(0, $message);
        }
        return ccia_return(1, $message);
    }


}