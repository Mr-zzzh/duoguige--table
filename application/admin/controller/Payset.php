<?php

namespace app\admin\controller;

/**
 * @title 支付设置
 * @description 支付设置
 * @group admin接口
 */
class Payset extends Common {
    /**
     * @title 获取支付配置列表
     * @description 获取支付配置列表
     * @author 开发者
     * @url /admin/payset/index
     * @method POST/GET
     *
     *
     * @return list:支付配置列表@
     * @list id:支付配置ID alipay_appId:阿里支付appid alipay_gatewayUrl:阿里支付网关 alipay_rsaPrivateKey:阿里支付私钥 alipay_alipayrsaPublicKey:阿里支付公钥 wxpay_APPID:微信支付appid wxpay_MCHID:微信支付商户号 wxpay_KEY:微信支付key wxpay_APPSECRET:微信支付应用密钥  wxpay_apiclient_cert:微信cert wxpay_apiclient_key:微信key证书 createtime:创建时间
     */
    public function index() {
        $payset = new \ccia\pay\Payset();
        $list   = $payset->getList();
        return myjson(1, array('list' => $list));
    }

    /**
     * @title 支付配置信息添加/修改
     * @description 支付信息添加
     * @author 开发者
     * @url /admin/payset/add
     * @method POST
     *
     * @param name:paytype require:1 type:int desc:1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付(paytype对应支付参数存在时,改接口可用于修改)
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
     *
     */
    public function add() {
        $payset = new \ccia\pay\Payset();
        $res    = $payset->add();
        $type   = ['1' => '阿里支付', '2' => '微信app支付', '3' => '微信H5支付', '4' => '微信小程序支付'];
        if ($res['status'] == 1) {
            olog('支付配置信息添加或修改【' . $type[$res['paytype']] . '】');
        }
        return myjson($res['status'], $res['message']);
    }

    /**
     * @title 获取支付配置信息
     * @description 获取支付配置信息
     * @author 开发者
     * @url /admin/payset/payget
     * @method POST/GET
     *
     * @param name:paytype require:0 type:int other:id、paytype必传一个 desc:支付类型1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:id require:0 type:int other:id、paytype必传一个 desc:支付信息ID
     *
     * @return payinfo:支付配置信息@!
     * @payinfo id:支付配置ID alipay_appId:阿里支付appid alipay_gatewayUrl:阿里支付网关 alipay_rsaPrivateKey:阿里支付私钥 alipay_alipayrsaPublicKey:阿里支付公钥 wxpay_APPID:微信支付appid wxpay_MCHID:微信支付商户号 wxpay_KEY:微信支付key wxpay_APPSECRET:微信支付应用密钥  wxpay_apiclient_cert:微信cert wxpay_apiclient_key:微信key证书
     */
    public function payget() {
        $payset = new \ccia\pay\Payset();
        $res    = $payset->getpayshow();
        if ($res['status'] == 0) {
            return myjson(0, $res['message']);
        }
        return myjson(1, $res['message'], $res['result']);
    }

    /**
     * @title 支付配置删除
     * @description 支付配置删除
     * @author 开发者
     * @url /admin/payset/delete
     * @method POST/GET
     *
     * @param name:paytype require:0 type:int other:id、paytype必传一个 desc:支付类型1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付
     * @param name:id require:0 type:int other:id、paytype必传一个 desc:支付信息ID
     *
     * @return null
     */
    public function delete() {
        $payset = new \ccia\pay\Payset();
        $res    = $payset->delete();
        if ($res['status'] == 0) {
            return myjson(0, $res['message']);
        }
        $type = ['1' => '阿里支付', '2' => '微信app支付', '3' => '微信H5支付', '4' => '微信小程序支付'];
        olog('支付配置信息删除【' . $type[$res['result']] . '】');
        return myjson(1, '删除成功!');
    }
}
