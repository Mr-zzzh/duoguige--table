<?php
namespace ccia\pay;

use think\Db;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Paylog extends Common
{
    //支付记录添加
    public function add($data){
        if (empty($data)){
            return ccia_return(0,'请选择支付记录');
        }
        $adddata = array(
            'ordersn'=>$data['ordersn'],
            'ordermoney'=>$data['ordermoney'],
            'paystatus'=>0,
            'paytype'=>$data['paytype']
        );
        $info = Db::table($this->PayLotTable)->where(array('ordersn'=>$data['ordersn']))->field('id,ordersn,paystatus')->find();
        if (!empty($info)){
            //已存在支付记录
            if ($info['paystatus'] != 0){
                return ccia_return(0,'订单状态不可支付');
            }
            $res = Db::table($this->PayLotTable)->where(array('id'=>$info['id']))->update($adddata);
            if ($res === false){
                return ccia_return(0,'支付记录更新失败');
            }
        }else{
            $adddata['createtime'] = time();
            $res = Db::table($this->PayLotTable)->insert($adddata);
            if ($res === false){
                return ccia_return(0,'支付记录添加失败');
            }
        }
        return ccia_return(1,'记录添加修改成功');

    }

}