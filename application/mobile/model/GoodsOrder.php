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

    public function EditOne($params, $id) {
        $data = array(
            'uid'         => intval($params['uid']),
            'gid'         => intval($params['gid']),
            'ordersn'     => trim($params['ordersn']),
            'number'      => intval($params['number']),
            'money'       => trim($params['money']) * 1,
            'status'      => intval($params['status']),
            'paytype'     => intval($params['paytype']),
            'tradeno'     => intval($params['tradeno']),
            'addressid'   => intval($params['addressid']),
            'freight'     => trim($params['freight']) * 1,
            'expresscom'  => trim($params['expresscom']),
            'expresssn'   => trim($params['expresssn']),
            'paytime'     => intval($params['paytime']),
            'finishtime'  => intval($params['finishtime']),
            'canceltime'  => intval($params['canceltime']),
            'delivertime' => intval($params['delivertime']),
        );
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
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