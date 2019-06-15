<?php
namespace ccia\pay;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Paying extends Common
{
    /**
     * 阿里系支付
     */
    //阿里支付aop渲染
    public function GetAop(){
        $payset = new Payset();
        $info = $payset->payget(1);
        if ($info['status'] == 0){
            return ccia_return(0,'支付宝支付配置信息获取错误');
        }
        $payinfo = $info['result'];
        require_once ROOT_PATH . 'extend/ccia/pay/alipay-sdk/AopSdk.php';
        $aop = new \AopClient();
        $aop->gatewayUrl = $payinfo['alipay_gatewayUrl'];
        $aop->appId = $payinfo['alipay_appId'];
        $aop->rsaPrivateKey = $payinfo['alipay_rsaPrivateKey'];
        $aop->alipayrsaPublicKey = $payinfo['alipay_alipayrsaPublicKey'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';
        return ccia_return(1,'获取支付宝支付配置成功',$aop);
    }
    //阿里app支付
    public function Aliapppay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        $res = $this->GetAop();
        if ($res['status'] == 0){
            return $res;
        }
        $aop = $res['result'];
        $orderinfo['timeout_express'] = $orderinfo['timeout_express']?:'90m';
        $ordermsg = array('body'=>$orderinfo['body'],'subject'=>$orderinfo['title'],'out_trade_no'=>$orderinfo['ordersn'],'timeout_express'=>$orderinfo['timeout_express'],'total_amount'=>$orderinfo['money']);
        $ordermsg['notify_url'] = $this->alinotify;
        $request = new \AlipayTradeAppPayRequest();
        $request->setBizContent(json_encode($ordermsg));
        $result = $aop->sdkExecute($request);
        return ccia_return(1,'阿里app支付创建成功',$result);
    }
    //阿里手机支付
    public function Alimobilepay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        $res = $this->GetAop();
        if ($res['status'] == 0){
            return $res;
        }
        $aop = $res['result'];
        $orderinfo['timeout_express'] = $orderinfo['timeout_express']?:'90m';
        $request = new \AlipayTradeWapPayRequest();
        $ordermsg = array('body'=>$orderinfo['body'],'subject'=>$orderinfo['title'],'out_trade_no'=>$orderinfo['ordersn'],'timeout_express'=>$orderinfo['timeout_express'],'total_amount'=>$orderinfo['money'],'product_code'=>QUICK_WAP_WAY);
        if (!empty($this->alinotify)){
            $request->setNotifyUrl($this->alinotify);
        }
        if (!empty($this->return_url)){
            $request->setReturnUrl($this->return_url);
        }
        $request->setBizContent(json_encode($ordermsg));
        $result = $aop->pageExecute($request);
        return ccia_return(1,'支付宝手机支付创建成功',$result);
    }
    //阿里web支付
    public function Aliwebpay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        $res = $this->GetAop();
        if ($res['status'] == 0){
            return $res;
        }
        $aop = $res['result'];
        $orderinfo['timeout_express'] = $orderinfo['timeout_express']?:'90m';
        $request = new \AlipayTradePagePayRequest();
        if ($this->alinotify){
            $request->setNotifyUrl($this->alinotify);
        }
        if ($this->return_url){
            $request->setReturnUrl($orderinfo['return_url']);
        }
        $ordermsg = array('product_code'=>'FAST_INSTANT_TRADE_PAY','subject'=>$orderinfo['title'],'out_trade_no'=>$orderinfo['ordersn'],'timeout_express'=>$orderinfo['timeout_express'],'total_amount'=>$orderinfo['money'],'body'=>$orderinfo['body']);
        $request->setBizContent(json_encode($ordermsg));
        $result = $aop->pageExecute($request);
        return ccia_return(1,'阿里PC端支付创建成功',$result);
    }





    /**
     * 微信系支付
     */
    //微信app支付
    public function Wxapppay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        $wxapp = new Wxapppay();
        $order = $wxapp->getPrePayOrder($orderinfo['body'], $orderinfo['ordersn'],intval(strval($orderinfo['money'] * 100)),$this->wxnotify);
        if ($order['prepay_id']){//判断返回参数中是否有prepay_id
            $order1 = $wxapp->getOrder($order['prepay_id']);//执行二次签名返回参数
            return ccia_return(1,'微信APP支付创建成功',$order1);
        } else {
            return ccia_return(0,$order['err_code_des']);
        }
    }
    //微信公众号支付
    public function Wxjsapipay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        if (empty($orderinfo['openid'])){
            return ccia_return(0,'未获取用户OPENID');
        }
        require_once ROOT_PATH."extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php";
        require_once ROOT_PATH."extend/ccia/pay/WxpayAPI/example/WxPay.JsApiPay.php";
        $input = new \WxPayUnifiedOrder();
        $tools = new \JsApiPay();
        //商品描述
        $input->SetBody($orderinfo['body']);
        //商户订单号--商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*且在同一个商户号下唯一。
        $input->SetOut_trade_no($orderinfo['ordersn']);
        //总金额
        $input->SetTotal_fee(intval(strval($orderinfo['money'] * 100)));
        //交易起始时间
        $input->SetTime_start(date("YmdHis"));
        //交易结束时间
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //openid
        $input->SetOpenid($orderinfo['openid']);
        if ($this->wxnotify){
            $input->SetNotify_url($this->wxnotify);
        }
        //交易类型--JSAPI 公众号支付 NATIVE 扫码支付 APP APP支付
        $input->SetTrade_type("JSAPI");
        $order = \WxPayApi::unifiedOrder($input);
        if ($order['return_code'] != 'SUCCESS'){
            return ccia_return(0,$order['return_msg']);
        }else{
            if ($order['result_code'] == 'FAIL'){
                return ccia_return(0,$order['err_code_des']);
            }
        }
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $jsApiParameters = json_decode($jsApiParameters,true);
        return ccia_return(1,'微信公众号支付创建成功',$jsApiParameters);
    }
    //微信小程序支付
    public function Wxminipay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        if (empty($orderinfo['code']) && empty($orderinfo['openid'])){
            return ccia_return(0,'小程序登录授权失败');
        }
        $payset = new Payset();
        $res = $payset->payget(4);
        if ($res['status'] == 0){
            return $res;
        }
        $payinfo = $res['result'];
        if (empty($orderinfo['openid'])){
            $res = $this->Miniopenid($payinfo['wxpay_APPID'],$payinfo['wxpay_APPSECRET'],$orderinfo['code']);
            if ($res['status'] == 0){
                return $res;
            }
            $orderinfo['openid'] = $res['result'];
        }
        require_once ROOT_PATH."extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php";
        require_once ROOT_PATH."extend/ccia/pay/WxpayAPI/example/WxPay.JsApiPay.php";
        $input = new \WxPayUnifiedOrder();
        $tools = new \JsApiPay();
        //商品描述
        $input->SetBody($orderinfo['body']);
        //商户订单号--商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*且在同一个商户号下唯一。
        $input->SetOut_trade_no($orderinfo['ordersn']);
        //总金额
        $input->SetTotal_fee($orderinfo['money']*100);
        //交易起始时间
        $input->SetTime_start(date("YmdHis"));
        //交易结束时间
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //openid
        $input->SetOpenid($orderinfo['openid']);
        if ($this->wxnotify){
            $input->SetNotify_url($this->wxnotify);
        }
        //交易类型--JSAPI 公众号支付 NATIVE 扫码支付 APP APP支付
        $input->SetTrade_type("JSAPI");
        $order = \WxPayApi::unifiedOrder($input);
        if ($order['return_code'] != 'SUCCESS'){
            return ccia_return(0,$order['return_msg']);
        }else{
            if ($order['result_code'] == 'FAIL'){
                return ccia_return(0,$order['err_code_des']);
            }
        }
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $jsApiParameters = json_decode($jsApiParameters,true);
        return ccia_return(1,'小程序支付创建成功',$jsApiParameters);
    }
    //小程序获取用户openid
    public function Miniopenid($wxpay_APPID,$wxpay_APPSECRET,$code){
        if (empty($wxpay_APPID) || empty($wxpay_APPSECRET) || empty($code)){
            return array('status'=>0,'message'=>'微信配置信息错误');
        }
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$wxpay_APPID.'&secret='.$wxpay_APPSECRET.'&js_code='.$code.'&grant_type=authorization_code';
        $res = go_curl($url);
        if ($res['status'] == 0){
            return ccia_return(0,$res['message']);
        }
        $result = json_decode($res['response'],true);
        if ($result['errcode'] != 0){
            return ccia_return(0,'获取用户信息失败');
        }else{
            return ccia_return(1,'获取用户信息成功',$result['openid']);
        }
    }
    //微信H5mobile支付
    public function Wxh5mobilepay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        require_once ROOT_PATH . 'extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php';
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($orderinfo['body']);
        $input->SetOut_trade_no($orderinfo['ordersn']);
        $input->SetTotal_fee(intval(strval($orderinfo['money'] * 100)));
        if ($this->wxnotify){
            $input->SetNotify_url($this->wxnotify);
        }
        $input->SetTrade_type("MWEB");
        $result = \WxPayApi::unifiedOrder($input);
        if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS' && isset($result['mweb_url'])) {
            return ccia_return(1, '手机端H5支付创建成功',$result['mweb_url'] . ( $this->return_url ? '&redirect_url=' . urlencode($this->wxnotify) : ''));
        } else {
            return ccia_return(0, $result['err_code_des']? : $result['return_msg']);
        }
    }
    //微信H5pc端支付
    public function Wxh5pcpay($orderinfo){
        if (empty($orderinfo)){
            return ccia_return(0,'订单信息错误');
        }
        require_once ROOT_PATH . 'extend/ccia/pay/WxpayAPI/lib/WxPay.Api.php';
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($orderinfo['body']);
        $input->SetOut_trade_no($orderinfo['ordersn']);
        $input->SetTotal_fee(intval(strval($orderinfo['money'] * 100)));
        if ($this->wxnotify){
            $input->SetNotify_url($this->wxnotify);
        }
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id(md5($orderinfo['ordersn']));
        $result = \WxPayApi::unifiedOrder($input);
        if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS' && isset($result['code_url'])) {
            $qrcode = cciaQrcode($result['code_url']);
            return ccia_return($qrcode ? 1 : 0,$qrcode?'pc端支付创建成功':'支付创建失败',$qrcode ? '<img src="data:image/png;base64,' . base64_encode(file_get_contents($qrcode)) . '"/>' : '支付二维码生成失败');
        } else {
            return ccia_return(0, $result['err_code_des']? : $result['return_msg']);
        }
    }




}