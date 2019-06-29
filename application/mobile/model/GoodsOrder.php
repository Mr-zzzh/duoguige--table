<?php

namespace app\mobile\model;

use ccia\pay\Pay;

class GoodsOrder extends Common {

    public function GetAll($params) {
        global $member;
        $map          = array();
        $map['a.uid'] = $member['id'];
        $list         = $this->alias('a')
            ->join('goods b', 'a.gid=b.id', 'left')
            ->field('a.id,a.ordersn,a.number,a.money,a.status,a.paytime,b.name,b.thumbnail,b.price,b.label')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array('-1' => '取消订单', '0' => '待支付', '1' => '待发货', '2' => '已发货', '3' => '已完成');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text'] = $status[$item['status']];
                $item['label']       = str_replace(',', '+', $item['label']);
                $item['paytime']     = date('Y-m-d H:i:s', $item['paytime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $id   = intval($params['id']);
        $type = intval($params['type']);
        if (empty($id)) {
            show_json(0, '请传订单id');
        }
        if ($type == 1) {
            $data['oid']        = $id;
            $data['status']     = 0;
            $data['createtime'] = time();
            if (db('remind')->insert($data)) {
                show_json(1, '操作成功');
            } else {
                show_json(0, '操作失败');
            }
        } elseif ($type == 2) {
            $data = array(
                'status'     => 3,
                'finishtime' => time(),
            );
            if ($this->save($data, array('id' => $id)) !== false) {
                show_json(1, '操作成功');
            } else {
                show_json(0, '操作失败');
            }
        } else {
            show_json(0, '所传类型不正确');
        }
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function Affirm($params) {
        global $member;
        $id = intval($params['id']);
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $shop = db('goods')->where('id', $id)->field('id,name,thumbnail,price,label')->find();
        if (empty($shop)) {
            show_json(0, '参数ID错误');
        }
        $shop['label']  = str_replace(',', '+', $shop['label']);
        $shop['number'] = 1;
        $address        = db('delivery_address')->where(array('uid' => $member['id'], 'default' => 1))->find();
        show_json(1, array('shop' => $shop, 'address' => $address));
    }

    public function pay($params) {
        global $member;
        $gid = intval($params['id']);
        if ($gid < 1) {
            show_json(0, '参数商品ID错误');
        }
        $addressid = intval($params['addressid']);
        if ($addressid < 1) {
            show_json(0, '参数地址ID错误');
        }
        $paytype = intval($params['paytype']);
        if (empty($paytype)) {
            show_json(0, '支付方式不能为空');
        }
        $money   = db('goods')->where('id', $gid)->value('price');
        $ordersn = 'YT' . random(6, true) . time();
        if ($money == 0) {
            $data = [
                'uid'        => $member['id'],
                'gid'        => $gid,
                'ordersn'    => $ordersn,
                'number'     => 1,
                'money'      => $money,
                'status'     => 1,
                'paytype'    => $paytype,
                'addressid'  => $addressid,
                'createtime' => time(),
                'paytime'    => time(),
            ];
            if ($this->data($data, true)->isUpdate(false)->save()) {
                show_json(2, '支付成功');
            } else {
                show_json(0, '支付失败');
            }
        }
        $data = [
            'uid'        => $member['id'],
            'gid'        => $gid,
            'ordersn'    => $ordersn,
            'number'     => 1,
            'money'      => $money,
            'status'     => 0,
            'paytype'    => $paytype,
            'addressid'  => $addressid,
            'createtime' => time(),
        ];
        if (!$this->data($data, true)->isUpdate(false)->save()) {
            show_json(0, '支付失败');
        }
        $pay     = new Pay();
        $payinfo = [
            'body'    => '云梯商品',
            'title'   => '云梯商品',
            'ordersn' => $ordersn,
            'money'   => $money,
            'money'   => '10m',
            'paytype' => $paytype,
        ];
        $res     = $pay->pay($payinfo);
        if ($res['status'] == 1) {
            show_json(1, $res['result']);
        } else {
            show_json(0, '支付失败');
        }
    }

    public function Notify($data) {
        $ordersn = $data['ordersn'];
        $order   = [
            'tradeno' => $data['trade_no'],
            'paytime' => time(),
        ];
        if ($data['paystatus'] == 1) {
            $order['status'] = 1;
        }
        if ($this->save($data, array('ordersn' => $ordersn)) !== false) {
            return true;
        } else {
            trace($data, 'payerror');
            return false;
        }
    }

    public function GetOne($id) {
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}