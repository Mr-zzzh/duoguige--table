<?php
namespace ccia\pay;
use think\Controller;
use think\Request;

require_once ROOT_PATH . 'extend/ccia/common.php';

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 15:06
 */
class Common extends Controller{
    protected $paytype = array(1,2,3,4); //支付方式1-阿里支付2-微信app支付3-微信公众号|H5支付4-微信小程序支付
    protected $client = 'web'; //支付客户端web->web端,非web字段则默认app端(可支付时传入)
    protected $wxnotify = '';//微信回调地址(可支付时传入)
    protected $alinotify = ''; //阿里回调地址(可支付时传入)
    protected $return_url = ''; //回跳地址部分支付方式可使用(可支付时传入)
    protected $PaySetTable = 'pay_set'; //支付配置表名
    protected $PayLotTable = 'pay_log'; //支付记录表名
    protected $PayRefundTable = 'refund_log'; //退款记录表名
    public function _initialize()
    {
        parent::_initialize();
        $this->wxnotify = url('mobile/notify/wx_notify','','',true);
        $this->alinotify = url('mobile/notify/ali_notify','','',true);
    }
}