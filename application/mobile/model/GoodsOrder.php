<?php

namespace app\mobile\model;

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