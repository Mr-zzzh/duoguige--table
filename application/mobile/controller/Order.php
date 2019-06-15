<?php
namespace app\mobile\controller;


use ccia\pay\Pay;
use think\Cache;
use think\Db;
use think\Request;

/**
 * @title 订单管理
 * @description 订单管理
 * @group mobile接口
 */
class Order extends Common
{
    /**
     * @title 订单支付
     * @description 订单支付
     * @author 开发者
     * @url /mobile/order/pay
     * @method POST
     * @param name:ordersn type:string  require:1 desc:订单编号
     * @param name:paytype type:int  require:1 desc:1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:client type:string  require:1 desc:web/app(阿里支付使用)
     * @param name:code type:string  require:0 desc:小程序支付时必填
     * @param name:return_url type:string  require:0 desc:支付成功的回跳地址
     *
     * @return info:订单支付信息@!
     * @info id:订单ID ordersn:订单编号 ordermoney:订单金额 paymoney:订单应付金额 credit2:订单抵扣余额 credit3:订单抵扣车巢币
     */
    public function pay($sn = '')
    {
        $params = Request::instance()->param();
        $ordersn = trim($params['ordersn'])?:$sn;
        if (Cache::get('pay_'.$ordersn)){
            return myjson(0,'请勿频繁操作!');
        }
        Cache::set('pay_'.$ordersn,true,3);
        $res = $this->getOrderInfo($ordersn,0);
        if ($res['status'] == 0){
            return myjson(0,$res['message']);
        }
        $orderinfo = $res['result'];
        if ($orderinfo['status'] != 0){
            return myjson(0,'订单状态错误,不可支付!');
        }
        if (in_array($params['paytype'],array(1,2,3,4))){
            $res = $this->changeOrder($orderinfo['ordersn'],array('paytype'=>$params['paytype']));
            if ($res['status'] == 0){
                return myjson(0,'订单支付方式修改失败');
            }
            if ($orderinfo['credit2'] == 0 && $orderinfo['credit3'] == 0){
                $res = deduction_pay($orderinfo);
                if ($res['status'] == 0){
                    return myjson(0,$res['message']);
                }
                $orderinfo = $res['result'];
            }
            if ($orderinfo['paymoney'] == 0){
                //抵扣支付完成
                $this->changeOrder($orderinfo['ordersn'],array('paytype'=>5));
                /*array('ordersn'=>'订单号','paytime'=>'支付时间','paystatus'=>'支付状态1-成功','paymoney'=>'支付金额')*/
                $data = array(
                    'ordersn'=>$orderinfo['ordersn'],
                    'paytime'=>time(),
                    'paystatus'=>1,
                    'paymoney'=>0
                );
                $paynotify = new Paynotification();
                $data['sign'] = get_sign($data);
                $paynotify->paysuccess($data);
                return myjson(10000,'抵扣支付成功!');
            }else{
                $closetime = get_set()['del']['order_close']?:10;
                $payinfo = array(
                    'body'=>'订单'.$orderinfo['ordersn'].'支付',
                    'title'=>$orderinfo['ordersn'],
                    'ordersn'=>$orderinfo['ordersn'],
                    'money'=>$orderinfo['paymoney'],
                    'timeout_express'=>$closetime.'m',
                    'paytype'=>$orderinfo['paytype']
                );
                $openid = cookie('wx__openid');
                if (!empty($openid)){
                    $payinfo['openid'] = $openid;
                }
                if (!empty($params['client'])){
                    $payinfo['client'] = $params['client'];
                }
                if (!empty($params['return_url'])){
                    $payinfo['return_url'] = $params['return_url'];
                }
            }
            //在线支付
            $pay = new Pay();
            $res = $pay->pay($payinfo);
            if ($res['status'] == 0){
                if ($orderinfo['credit2'] > 0){
                    credit_change($orderinfo['mid'],$orderinfo['credit2'],1,1,9,$orderinfo['ordersn'],'异常回退');
                }
                if ($orderinfo['credit3'] > 0){
                    credit_change($orderinfo['mid'],$orderinfo['credit3'],1,2,9,$orderinfo['ordersn'],'异常回退');
                }
                $this->changeOrder($orderinfo['ordersn'],array('paymoney'=>$orderinfo['ordermoney'],'credit2'=>0,'credit3'=>0));
                return myjson(0,$res['message']);
            }
            if ($payinfo['paytype'] == 1){
                $payres = $this->changeOrder($payinfo['ordersn'],array('alipayinfo'=>$res['result']));
                if ($payres){
                    $res['result'] = url('mobile/index/alipay',array('ordersn'=>$payinfo['ordersn']),'',true);
                }
            }
            return myjson(1,'订单支付创建成功!',$res['result']);
        }else{
            return myjson(0,'支付方式选择错误!');
        }
    }
    /**
     * @title 创建升级订单
     * @description 创建升级订单
     * @author 开发者
     * @url /mobile/order/createLevelUp
     * @method POST
     * @param name:goods[0][id] type:int  require:1 desc:购买商品ID
     * @param name:goods[0][number] type:int  require:1 desc:购买商品数量
     * @param name:mobile type:string  require:1 desc:邀请者手机号
     *
     * @return null
     */
    public function createLevelUp(){
        $params = Request::instance()->param();
        $llModel = new \app\mobile\model\LevelupLog();
        $res = $llModel->createOrder($params);
        if ($res === false){
            return myjson(0,$llModel->getError());
        }
        return myjson(1,array('ordersn'=>$res['ordersn']));
    }
    /**
     * @title 订单支付信息获取
     * @description 订单支付信息获取
     * @author 开发者
     * @url /mobile/order/payinfo
     * @method POST
     * @param name:ordersn type:string  require:1 desc:订单编号
     *
     * @return info:订单支付信息@!
     * @info id:订单ID ordersn:订单编号 ordermoney:订单金额 paymoney:订单应付金额 credit2:订单抵扣余额 credit3:订单抵扣车巢币
     */
    public function payinfo(){
        $params = Request::instance()->param();
        $ordersn = trim($params['ordersn']);
        if (strexists($ordersn,'LU')){
            //升级订单
            return $this->levelpayinfo($ordersn);
        }else if (strexists($ordersn,'AD')){
            return $this->adorderinfo($ordersn);
        }else if (strexists($ordersn,'CZ')){
            return $this->czorderinfo($ordersn);
        }else{
            return myjson(0,'订单号异常!');
        }
    }
    /**
     * @title 订单支付查询
     * @description 订单支付查询
     * @author 开发者
     * @url /mobile/order/query
     * @method POST/GET
     *
     * @param name:ordersn require:1 type:string other:查询订单号
     *
     * @return status:查询状态(-1-订单支付失败或关闭,0-订单查询失败(可多次查询,一定次数失败后跳转失败页),1-支付成功)
     * @return message:查询提示信息
     */
    public function query(){
        $params = Request::instance()->param();
        $ordersn = trim($params['ordersn']);
        if (empty($ordersn)){
            return myjson(-1,'请选择订单');
        }
        if (strexists($ordersn,'LU')){
            $ordertable = 'levelup_log';
        }elseif(strexists($ordersn,'AD')){
            $ordertable = 'advorder';
        }elseif(strexists($ordersn,'CZ')){
            $ordertable = 'credit2_add';
        }else{
            return myjson(-1,'订单号异常!');
        }
        $payinfo = Db::name($ordertable)->where(array('ordersn'=>$ordersn))->field('paytype,status')->find();
        if (empty($payinfo)){
            return myjson(-1,'支付方式错误');
        }
        if ($payinfo['paytype'] == 5 || $payinfo['paytype'] == 6){
            if ($payinfo['status'] < 1){
                return myjson(-1,'抵扣支付失败!');
            }
        }
        $pay = new Pay();
        $res = $pay->query($ordersn);
        return myjson($res['status'],$res['message']);
    }
    //升级订单支付信息获取
    public function levelpayinfo($ordersn){
        if (empty($ordersn)){
            return myjson(0,'请选择订单!');
        }
        $lllModel = new \app\mobile\model\LevelupLog();
        $info = $lllModel->where(array('ordersn'=>$ordersn))->field('id,paytype,mid,ordersn,status,ordermoney,paymoney,credit2')->find();
        if (empty($info)){
            return myjson(0,'未找到订单信息!');
        }
        $info = $info->toArray();
        if ($info['status'] == 0 && empty($info['paytype'])){
            //未支付计算抵扣金额
            $creditinfo = order_deduction($info['ordermoney']);
        }else{
            //已支付或已创建支付订单
            $creditinfo = ['credit2'=>$info['credit2'],'credit3'=>$info['credit3']];
        }
        if ($creditinfo === false){
            return myjson(0,'用户金额抵扣错误!');
        }
        $info['paymoney'] = to_strval($info['ordermoney']-$creditinfo['credit2']-$creditinfo['credit3']);
        $info['credit2'] = to_strval($creditinfo['credit2']);
        $info['credit3'] = to_strval($creditinfo['credit3']);
        return myjson(1,array('info'=>$info));
    }
    //广告订单支付信息获取
    public function adorderinfo($ordersn){
        if (empty($ordersn)){
            return myjson(0,'请选择订单!');
        }
        $advModel = new \app\mobile\model\Advorder();
        $info = $advModel->where(array('ordersn'=>$ordersn))->field('id,ordersn,status,ordermoney,paymoney,credit2,credit3')->find();
        if (empty($info)){
            return myjson(0,'未找到订单信息!');
        }
        $info = $info->toArray();
        if ($info['status'] == 0 && empty($info['paytype'])){
            //未支付计算抵扣金额
            $creditinfo = order_deduction($info['ordermoney'],2);
        }else{
            //已支付或已创建支付订单
            $creditinfo = ['credit2'=>$info['credit2'],'credit3'=>$info['credit3']];
        }
        if ($creditinfo === false){
            return myjson(0,'用户金额抵扣错误!');
        }
        $info['paymoney'] = to_strval($info['ordermoney']-$creditinfo['credit2']-$creditinfo['credit3']);
        $info['credit2'] = to_strval($creditinfo['credit2']);
        $info['credit3'] = to_strval($creditinfo['credit3']);
        return myjson(1,array('info'=>$info));
    }
    //充值订单信息
    public function czorderinfo($ordersn){
        if (empty($ordersn)){
            return myjson(0,'请选择订单!');
        }
        $creditModel = new \app\mobile\model\Credit2Add();
        $info = $creditModel->where(array('ordersn'=>$ordersn))->field('id,ordersn,status,ordermoney,paymoney,credit2,credit3')->find();
        if (empty($info)){
            return myjson(0,'未找到订单信息!');
        }
        $info = $info->toArray();
        /*if ($info['status'] != 0){
            return myjson(0,'订单状态错误,禁止支付!');
        }*/
        $info['credit2'] = 0;
        $info['credit3'] = 0;
        return myjson(1,array('info'=>$info));
    }
    //获取订单信息
    public function getOrderInfo($ordersn,$status = ''){
        if (empty($ordersn)){
            return arr_return(0,'未找到订单信息!');
        }
        $tablename = '';
        $map = array();
        if (strexists($ordersn,'LU')){
            $tablename = 'levelup_log';
        }elseif (strexists($ordersn,'AD')){
            $tablename = 'advorder';
        }elseif (strexists($ordersn,'CZ')){
            $tablename = 'credit2_add';
        }
        if (empty($tablename)){
            return arr_return(0,'订单编号错误!');
        }
        $map['ordersn'] = $ordersn;
        if (is_numeric($status)){
            $map['status'] = $status;
        }
        $orderinfo = Db::name($tablename)->where($map)->find();
        if (empty($orderinfo)){
            return arr_return(0,'未找到订单信息!');
        }
        return arr_return(1,'获取订单信息成功',$orderinfo);
    }
    //更改订单信息
    public function changeOrder($ordersn,$data){
        if (empty($ordersn)){
            return arr_return(0,'未找到订单信息!');
        }
        $tablename = '';
        if (strexists($ordersn,'LU')){
            $tablename = 'levelup_log';
        }elseif (strexists($ordersn,'AD')){
            $tablename = 'advorder';
        }elseif (strexists($ordersn,'CZ')){
            $tablename = 'credit2_add';
        }
        if (empty($tablename)){
            return arr_return(0,'订单编号错误!');
        }
        $res = Db::name($tablename)->where(array('ordersn'=>$ordersn))->update($data);
        if ($res === false){
            return arr_return(0,'订单更新失败');
        }
        $info = Db::name($tablename)->where(array('ordersn'=>$ordersn))->find();
        return arr_return(1,'订单更新成功',$info);
    }
}
