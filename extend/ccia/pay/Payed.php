<?php
namespace ccia\pay;



use think\Db;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Payed extends Common
{
    //订单查询
    public function order_query($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'请选择订单');
        }
        if (!in_array($orderinfo['paytype'],$this->paytype)){
            return ccia_return(0,'订单支付方式错误');
        }
        if ($orderinfo['paytype'] == 1){
            //阿里订单支付查询
            $res = $this->ali_query($orderinfo);
        }else{
            //微信订单支付查询
            $res = $this->wx_query($orderinfo);
        }
        return $res;
    }
    //阿里订单查询
    public function ali_query($orderinfo){
        $ordersn = $orderinfo['ordersn'];
        $paying = new Paying();
        $res = $paying->GetAop();
        if ($res['status'] == 0){
            return ccia_return(0,'支付宝配置错误');
        }
        $aop = $res['result'];
        $querydata = array('out_trade_no'=>$ordersn);
        $request = new \AlipayTradeQueryRequest();
        $request->setBizContent(json_encode($querydata));
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultInfo = json_decode(json_encode($result->$responseNode), TRUE);
        $map = array('WAIT_BUYER_PAY' => '交易创建，等待买家付款', 'TRADE_CLOSED' => '未付款交易超时关闭，或支付完成后全额退款', 'TRADE_SUCCESS' => '交易支付成功', 'TRADE_FINISHED' => '交易结束，不可退款');
        if (!empty($resultInfo['code']) && $resultInfo['code'] == 10000 && $resultInfo['trade_status'] == 'TRADE_SUCCESS') {
            if (empty($resultInfo['send_pay_date'])) {
                $resultInfo['send_pay_date'] = time();
            } else {
                $resultInfo['send_pay_date'] = strtotime($resultInfo['send_pay_date']);
            }
            return ccia_return(1,$map[$resultInfo['trade_status']], array('trade_no'=>$resultInfo['trade_no'],'paytime'=>strtotime($resultInfo['send_pay_date']),'paymoney'=>$resultInfo['buyer_pay_amount']));
        } else if (!empty($resultInfo['code']) && $resultInfo['code'] == 10000 && !empty($map[$resultInfo['trade_status']])) {
            return ccia_return(0,$map[$resultInfo['trade_status']]);
        } else {
            return ccia_return(0,empty($map[$resultInfo['trade_status']]) ? (empty($resultInfo['sub_msg']) ? $resultInfo['msg'] : $resultInfo['sub_msg']) : $map[$resultInfo['trade_status']]);
        }
    }
    //微信订单支付查询
    public function wx_query($orderinfo){
        $GLOBALS['ccia_pay_type'] = $orderinfo['paytype'];
        require_once ROOT_PATH . 'extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php';
        $queryOrderInput = new \WxPayOrderQuery();
        $queryOrderInput->SetOut_trade_no($orderinfo['ordersn']);
        $result = \WxPayApi::orderQuery($queryOrderInput);
        $resultInfo = json_decode(json_encode($result), TRUE);
        $map = array('SUCCESS' => '支付成功', 'REFUND' => '转入退款', 'NOTPAY' => '未支付', 'CLOSED' => '已关闭', 'REVOKED' => '已撤销', 'USERPAYING' => '用户支付中', 'PAYERROR' => '支付失败',);
        if ($resultInfo['result_code'] == 'SUCCESS' && $resultInfo['return_code'] == 'SUCCESS' && $resultInfo['trade_state'] == 'SUCCESS') {
            if (empty($resultInfo['time_end'])) {
                $resultInfo['time_end'] = time();
            } else {
                $resultInfo['time_end'] = strtotime($resultInfo['time_end']);
            }
            return ccia_return(1,$map[$resultInfo['trade_state']], array('trade_no'=>$resultInfo['transaction_id'],'paytime'=>strtotime($resultInfo['time_end']),'paymoney'=>$resultInfo['total_fee']/100));
        } else if ($resultInfo['result_code'] == 'SUCCESS' && $resultInfo['return_code'] == 'SUCCESS' && !empty($map[$resultInfo['trade_state']])) {
            return ccia_return(0,$map[$resultInfo['trade_state']]);
        } else {
            $map = array('ORDERNOTEXIST' => '此交易订单号不存在', 'SYSTEMERROR' => '系统错误',);
            return ccia_return(0,empty($map[$resultInfo['err_code']]) ? (empty($resultInfo['err_code_des']) ? $resultInfo['return_msg'] : $resultInfo['err_code_des']) : $map[$resultInfo['err_code']]);
        }
    }

    //订单退款
    public function order_refund($refundsn){
        if (empty($refundsn)){
            return ccia_return(0,'请选择退款记录');
        }
        $refundinfo = Db::table($this->PayRefundTable)->where(array('refundsn'=>$refundsn))->find();
        if (empty($refundinfo)){
            return ccia_return(0,'未找到对应的退款记录');
        }
        if ($refundinfo['refundstatus'] != 0){
            return ccia_return(0,'退款状态禁止处理');
        }
        if ($refundinfo['paytype'] == 1){
            //支付宝退款
            $res = $this->ali_refund($refundinfo);
        }else{
            //微信退款
            $res = $this->wx_refund($refundinfo);
        }
        return $res;

    }
    //支付宝退款
    public function ali_refund($refundinfo){
        $paying = new Paying();
        $res = $paying->GetAop();
        if ($res['status'] == 0){
            return $res;
        }
        $aop = $res['result'];
        $request = new \AlipayTradeRefundRequest();
        $ordermsg = array('out_trade_no'=>$refundinfo['ordersn'],'refund_amount'=>$refundinfo['refundmoney'],'refund_currency'=>'CNY','out_request_no'=>$refundinfo['refundsn']);
        if (!empty($refundinfo['reason'])){
            $ordermsg['refund_reason']=$refundinfo['reason'];
        }
        $request->setBizContent(json_encode($ordermsg));
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            return ccia_return(1,'退款成功');
        } else {
            return ccia_return(0,'退款失败');
        }
    }
    //微信退款
    public function wx_refund($refundinfo){
        $GLOBALS['ccia_pay_type'] = $refundinfo['paytype'];
        require_once ROOT_PATH."extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php";
        require_once ROOT_PATH . 'extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php';
        $out_trade_no = $refundinfo["ordersn"];
        $total_fee = intval($refundinfo["ordermoney"]*100);
        $refund_fee = intval($refundinfo["refundmoney"]*100);
        $input = new \WxPayRefund();
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);
        //退款订单号
        $input->SetOut_refund_no($refundinfo['refundsn']);
        //操作员账号，默认为商户号
        $input->SetOp_user_id(\WxPayConfig::MCHID);
        $res = \WxPayApi::refund($input);
        if ($res['return_code'] == 'SUCCESS'){
            return ccia_return(1,'退款成功');
        }else{
            return ccia_return(0,'退款失败');
        }
    }

    //订单取消关闭
    public function order_cancel($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'请选择订单');
        }
        if ($orderinfo['paytype'] == 1){
            //支付宝订单关闭
            $res = $this->ali_cancel($orderinfo['ordersn']);
        }else{
            //微信订单关闭
            $res = $this->wx_cancel($orderinfo['ordersn']);
        }
        return $res;
    }
    //支付宝订单关闭
    public function ali_cancel($ordersn){
        $paying = new Paying();
        $res = $paying->GetAop();
        if ($res['status'] == 0){
            return $res;
        }
        $aop = $res['result'];
        $ordermsg = array('out_trade_no' => $ordersn);
        $request = new \AlipayTradeCancelRequest();
        $request->setBizContent(json_encode($ordermsg));
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultInfo = json_decode(json_encode($result->$responseNode), TRUE);
        if (!empty($resultInfo['code']) && $resultInfo['code'] == 10000 && $resultInfo['retry_flag'] == 'N') {
            return ccia_return(1, $resultInfo['message']);
        } else {
            return array(0,empty($resultInfo['sub_msg']) ? $resultInfo['message'] : $resultInfo['sub_msg']);
        }
    }
    //微信订单关闭
    public function wx_cancel($ordersn){
        $paytype = Db::table($this->PayLotTable)->where(array('ordersn'=>$ordersn))->value('paytype');
        $GLOBALS['ccia_pay_type'] = $paytype;
        require_once ROOT_PATH . 'extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php';
        $clostOrder = new \WxPayReverse();
        $clostOrder->SetOut_trade_no($ordersn);
        $result = \WxPayApi::closeOrder($clostOrder);
        $resultInfo = json_decode(json_encode($result), TRUE);
        if ($resultInfo['result_code'] == 'SUCCESS' && $resultInfo['return_code'] == 'SUCCESS') {
            return ccia_return(1, $resultInfo['return_msg']);
        } else {
            $map = array('SYSTEMERROR' => '接口返回错误', 'INVALID_TRANSACTIONID' => '无效订单号', 'PARAM_ERROR' => '参数错误', 'REQUIRE_POST_METHOD' => '请使用post方法', 'SIGNERROR' => '签名错误', 'REVERSE_EXPIRE' => '订单无法撤销', 'INVALID_REQUEST' => '无效请求', 'TRADE_ERROR' => '订单错误',);
            return ccia_return(0, empty($map[$resultInfo['err_code']]) ? (empty($resultInfo['err_code_des']) ? $resultInfo['return_msg'] : $resultInfo['err_code_des']) : $map[$resultInfo['err_code']]);
        }
    }

}