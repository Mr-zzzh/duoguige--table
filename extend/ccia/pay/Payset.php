<?php
namespace ccia\pay;
use think\Db;
use think\Request;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/31
 * Time: 14:58
 */
class Payset extends Common
{
    /**
     * @title 支付配置信息添加/修改
     * @description 支付信息添加
     * @author 开发者
     * @url /add
     * @method POST
     *
     * @param name:paytype require:1 type:int desc:1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:alipay_appId require:0 type:string desc:阿里支付appid(阿里支付必上传参数)
     * @param name:alipay_gatewayUrl require:0 type:string desc:阿里支付网关(阿里支付必上传参数)
     * @param name:alipay_rsaPrivateKey require:0 type:string desc:阿里支付私钥(阿里支付必上传参数)
     * @param name:alipay_alipayrsaPublicKey require:0 type:string desc:阿里支付公钥(阿里支付必上传参数)
     * @param name:wxpay_APPID require:0 type:string desc:微信支付appid(微信支付必上传参数)
     * @param name:wxpay_MCHID require:0 type:string desc:微信支付商户号(微信支付必上传参数)
     * @param name:wxpay_KEY require:0 type:string desc:微信支付key(微信支付必上传参数)
     * @param name:wxpay_APPSECRET require:0 type:string desc:微信支付应用密钥(微信支付必上传参数)
     * @param name:wxpay_apiclient_cert require:0 type:string desc:微信cert(微信支付必上传参数)
     * @param name:wxpay_apiclient_key require:0 type:string desc:微信key证书(微信支付必上传参数)
     */
    public function add(){
        if (Request::instance()->isPost()){
            $params = Request::instance()->param();
            if (!in_array($params['paytype'],array('1','2','3','4'))){
                return ccia_return(0,'支付方式设置错误');
            }
            if ($params['paytype'] == 1){
                //阿里支付设置
                $data = array(
                    'alipay_appId'=>$params['alipay_appId'],
                    'alipay_gatewayUrl'=>$params['alipay_gatewayUrl']
                );
                if(strlen($params['alipay_rsaPrivateKey'])>50){
                    $data['alipay_rsaPrivateKey'] = $params['alipay_rsaPrivateKey'];
                }
                if(strlen($params['alipay_alipayrsaPublicKey'])>50) {
                    $data['alipay_alipayrsaPublicKey'] = $params['alipay_alipayrsaPublicKey'];
                }
            }else{
                //微信支付设置
                $url = ROOT_PATH.'application/addons/pay/WxpayAPI/cert/';
                $data['wxpay_SSLCERT_PATH'] = $url.md5($params['wxpay_apiclient_cert']).'.pem';
                $data['wxpay_SSLKEY_PATH'] = $url.md5($params['wxpay_apiclient_key']).'.pem';
                if (!is_dir($url)) {
                    mkdirs2($url);
                }
                file_put_contents($data['wxpay_SSLCERT_PATH'],$params['wxpay_apiclient_cert']);
                file_put_contents($data['wxpay_SSLKEY_PATH'],$params['wxpay_apiclient_key']);
                if(strlen($params['wxpay_apiclient_cert'])>50){
                    $data['wxpay_apiclient_cert'] = $params['wxpay_apiclient_cert'];
                }
                if(strlen($params['wxpay_apiclient_key'])>50) {
                    $data['wxpay_apiclient_key'] = $params['wxpay_apiclient_key'];
                }
                $data['wxpay_APPID'] = $params['wxpay_APPID'];
                $data['wxpay_MCHID'] = $params['wxpay_MCHID'];
                $data['wxpay_KEY'] = $params['wxpay_KEY'];
                $data['wxpay_APPSECRET'] = $params['wxpay_APPSECRET'];
            }
            $data['paytype'] = intval($params['paytype']);
            $map = array();
            $map['paytype'] = intval($params['paytype']);
            $id = Db::table($this->PaySetTable)->where($map)->value('id');
            if (!empty($id)){
                $res = Db::table($this->PaySetTable)->where(array('id'=>$id))->update($data);
            }else{
                $data['createtime'] = time();
                $res = Db::table($this->PaySetTable)->insert($data);
            }
            if ($res === false){
                return ccia_return(0,'支付配置失败');
            }
            return ccia_return(1,'支付配置成功',$params['paytype']);
        }
        return ccia_return(0,'请求方式错误');
    }
    /**
     * @title 获取支付配置信息
     * @description 获取支付配置信息
     * @author 开发者
     * @url /payget
     * @method POST/GET
     *
     * @param name:paytype require:0 type:int other:id、paytype必传一个 desc:支付类型1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:id require:0 type:int other:id、paytype必传一个 desc:支付信息ID
     *
     * @return payinfo:支付配置信息@
     * @payinfo alipay_appId:阿里支付appid alipay_gatewayUrl:阿里支付网关 alipay_rsaPrivateKey:阿里支付私钥 alipay_alipayrsaPublicKey:阿里支付公钥 wxpay_APPID:微信支付appid wxpay_MCHID:微信支付商户号 wxpay_KEY:微信支付key wxpay_APPSECRET:微信支付应用密钥  wxpay_apiclient_cert:微信cert wxpay_apiclient_key:微信key证书
     */
    public function payget($paytype = ''){
        global $ccia_pay_type;
        $params = Request::instance()->param();
        if (empty($paytype)){
            $paytype = intval($params['paytype']);
            $id = intval($params['id']);
        }
        if (empty($paytype)){
            $paytype = $ccia_pay_type;
        }
        if (empty($paytype) && empty($id)){
            return ccia_return(0,'请选择支付方式');
        }
        $map = array();
        if (!empty($paytype)){
            $map['paytype'] = $paytype;
        }
        if (!empty($id)){
            $map['id'] = $id;
        }
        $info = Db::table($this->PaySetTable)->where($map)->find();
        if (empty($info)){
            return ccia_return(0,'未找到支付配置');
        }
        return ccia_return(1,'获取支付配置成功',$info);
    }
    /**
     * @title 获取支付配置列表
     * @description 获取支付配置列表
     * @author 开发者
     * @url /getpayshow
     * @method POST/GET
     *
     *
     * @return list:支付配置列表@
     * @list id:支付配置ID alipay_appId:阿里支付appid alipay_gatewayUrl:阿里支付网关 alipay_rsaPrivateKey:阿里支付私钥 alipay_alipayrsaPublicKey:阿里支付公钥 wxpay_APPID:微信支付appid wxpay_MCHID:微信支付商户号 wxpay_KEY:微信支付key wxpay_APPSECRET:微信支付应用密钥  wxpay_apiclient_cert:微信cert wxpay_apiclient_key:微信key证书
     */
    public function getList(){
        $list = Db::table($this->PaySetTable)->select();
        if (!empty($list)){
            foreach ($list as &$v){
                if(!empty($v['wxpay_apiclient_cert'])){
                    $v['wxpay_apiclient_cert'] = substr($v['wxpay_apiclient_cert'],0,3).'*****'.substr($v['wxpay_apiclient_key'],'-3','-1');
                }
                if(!empty($v['wxpay_apiclient_key'])){
                    $v['wxpay_apiclient_key'] = substr($v['wxpay_apiclient_key'],0,3).'*****'.substr($v['wxpay_apiclient_key'],'-3','-1');
                }
                if(!empty($v['alipay_rsaPrivateKey'])){
                    $v['alipay_rsaPrivateKey'] = substr($v['alipay_rsaPrivateKey'],0,3).'*****'.substr($v['alipay_rsaPrivateKey'],'-3','-1');
                }
                if(!empty($v['alipay_alipayrsaPublicKey'])){
                    $v['alipay_alipayrsaPublicKey'] = substr($v['alipay_alipayrsaPublicKey'],0,3).'*****'.substr($v['alipay_alipayrsaPublicKey'],'-3','-1');
                }
                $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
                unset($v['wxpay_SSLCERT_PATH'],$v['wxpay_SSLKEY_PATH']);
            }
            unset($v);
        }
        return $list;
    }
    /**
     * @title 获取支付配置信息
     * @description 获取支付配置信息
     * @author 开发者
     * @url /getpayshow
     * @method POST/GET
     *
     * @param name:paytype require:0 type:int other:id、paytype必传一个 desc:支付类型1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:id require:0 type:int other:id、paytype必传一个 desc:支付信息ID
     *
     * @return payinfo:支付配置信息@!
     * @payinfo alipay_appId:阿里支付appid alipay_gatewayUrl:阿里支付网关 alipay_rsaPrivateKey:阿里支付私钥 alipay_alipayrsaPublicKey:阿里支付公钥 wxpay_APPID:微信支付appid wxpay_MCHID:微信支付商户号 wxpay_KEY:微信支付key wxpay_APPSECRET:微信支付应用密钥  wxpay_apiclient_cert:微信cert wxpay_apiclient_key:微信key证书
     */
    public function getpayshow($paytype = ''){
        $res = $this->payget($paytype);
        if (!empty($res['result'])){
            if(!empty($res['result']['wxpay_apiclient_cert'])){
                $res['result']['wxpay_apiclient_cert'] = substr($res['result']['wxpay_apiclient_cert'],0,3).'*****'.substr($res['result']['wxpay_apiclient_key'],'-3','-1');
            }
            if(!empty($res['result']['wxpay_apiclient_key'])){
                $res['result']['wxpay_apiclient_key'] = substr($res['result']['wxpay_apiclient_key'],0,3).'*****'.substr($res['result']['wxpay_apiclient_key'],'-3','-1');
            }
            if(!empty($res['result']['alipay_rsaPrivateKey'])){
                $res['result']['alipay_rsaPrivateKey'] = substr($res['result']['alipay_rsaPrivateKey'],0,3).'*****'.substr($res['result']['alipay_rsaPrivateKey'],'-3','-1');
            }
            if(!empty($res['result']['alipay_alipayrsaPublicKey'])){
                $res['result']['alipay_alipayrsaPublicKey'] = substr($res['result']['alipay_alipayrsaPublicKey'],0,3).'*****'.substr($res['result']['alipay_alipayrsaPublicKey'],'-3','-1');
            }
            unset($res['result']['wxpay_SSLCERT_PATH'],$res['result']['wxpay_SSLKEY_PATH']);
        }
        return $res;
    }
    /**
     * @title 支付配置删除
     * @description 支付配置删除
     * @author 开发者
     * @url /delete
     * @method POST/GET
     *
     * @param name:paytype require:0 type:int other:id、paytype必传一个 desc:支付类型1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:id require:0 type:int other:id、paytype必传一个 desc:支付信息ID
     *
     */
    public function delete(){
        $params = Request::instance()->param();
        $paytype = intval($params['paytype']);
        $id = intval($params['id']);
        $map = array();
        if (!empty($paytype)){
            $map['paytype'] = intval($params['paytype']);
        }
        if (!empty($id)){
            $map['id'] = $id;
        }
        $findid = Db::table($this->PaySetTable)->where($map)->value('id');
        if (empty($findid)){
            return ccia_return(0,'未找到支付配置信息');
        }
        $res = Db::table($this->PaySetTable)->where(array('id'=>$findid))->delete();
        if ($res === false){
            return ccia_return(0,'删除失败');
        }
        return ccia_return(1,'删除成功',$paytype);
    }
}