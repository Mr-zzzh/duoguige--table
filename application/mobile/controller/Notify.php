<?php
namespace app\mobile\controller;

use ccia\pay\Pay;
use ccia\pay\Paying;
use ccia\pay\Payset;
use think\Controller;
use think\Db;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Notify extends Controller
{
    protected $PaySetTable = 'pay_set';
    protected $PayLotTable = 'pay_log';
    //阿里回调通知
    public function ali_notify(){
        $data = input('post.');
        $out_trade_no = $data['out_trade_no'];
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn'=>$out_trade_no))->find();
        if (empty($orderinfo)){
            die('fail');
        }
        $paying = new Paying();
        $res = $paying->GetAop();
        if ($res['status'] == 0){
            die('fail');
        }
        $aop = $res['result'];
        $result = $aop->rsaCheckV1(request()->post(), '', 'RSA2');
        if ($result){
            $trade_status = $data['trade_status'];
            if ($trade_status == 'TRADE_FINISHED' || $trade_status =='TRADE_SUCCESS'){
                $order_update = array(
                    'paytime' => strtotime($data['gmt_payment']),
                    'paytype' => '1',
                    'paymoney' => $data['buyer_pay_amount'],
                    'paystatus' => 1,
                    'trade_no' => $data['trade_no'],
                    'ordersn'=>$data['out_trade_no']
                );
            }else if ($trade_status == 'TRADE_CLOSED'){
                $order_update = array(
                    'paytime' => strtotime($data['gmt_payment']),
                    'paytype' => '1',
                    'paystatus' => -1,
                    'trade_no' => $data['trade_no'],
                    'ordersn'=>$data['out_trade_no']
                );
            }
            if (!empty($order_update)){
                if ($orderinfo['paystatus'] >= 1){
                    die('success');
                }
                $pay = new Pay();
                //支付回调通知处理
                $res = $pay->paynotify($order_update);
                if ($res != false){
                    die('success');
                }
            }
        }
    }
    //微信回调通知
    public function wx_notify(){
        $xmlData = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $out_trade_no = $data['out_trade_no'];
        $orderinfo = Db::table($this->PayLotTable)->where(array('ordersn'=>$out_trade_no))->find();
        if (empty($orderinfo)){
            return false;
        }
        $payset = new Payset();
        $res = $payset->payget($orderinfo['paytype']);
        if ($res['status'] == 0){
            return false;
        }
        $payinfo = $res['result'];
        if (empty($payinfo)){
            return false;
        }
        ksort($data);
        $buff = '';
        foreach ($data as $k => $v){
            if($k != 'sign'){
                $buff .= $k . '=' . $v . '&';
            }
        }
        $stringSignTemp = $buff . 'key='.$payinfo['wxpay_KEY'];
        $sign = strtoupper(md5($stringSignTemp));
        if($sign == $data['sign']){
            $result_code = $data['result_code'];
            if ($result_code == 'SUCCESS'){
                $order_update = array(
                    'paytime' => strtotime($data['time_end']),
                    'paymoney' => $data['total_fee']/100,
                    'paystatus' => 1,
                    'trade_no' => $data['transaction_id'],
                    'paytype' => $orderinfo['paytype'],
                    'ordersn'=>$data['out_trade_no']
                );
            }else if ($result_code == 'FAIL'){
                $order_update = array(
                    'paytime' => strtotime($data['time_end']),
                    'paystatus' => -1,
                    'trade_no' => $data['transaction_id'],
                    'paytype' => $orderinfo['paytype'],
                    'ordersn'=>$data['out_trade_no']
                );
            }
            if (!empty($order_update)){
                if ($orderinfo['paystatus'] >= 1){
                    echo '<xml>
                          <return_code><![CDATA[SUCCESS]]></return_code>
                          <return_msg><![CDATA[OK]]></return_msg>
                          </xml>';
                    exit();
                }
                $pay = new Pay();
                $res = $pay->paynotify($order_update);
                if ($res != false){
                    //处理完成之后，告诉微信成功结果
                    echo '<xml>
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                    </xml>';
                    exit();
                }
            }
        }
    }

}