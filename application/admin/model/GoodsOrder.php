<?php

namespace app\admin\model;

class GoodsOrder extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['a.status'] = intval($params['status']);
        }
        if (!empty($params['keyword'])) {
            $map['a.ordersn|a.expresscom|u.phone|u.name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('goods g', 'a.gid=g.id', 'left')
            ->join('delivery_address d', 'a.addressid=d.id', 'left')
            ->field('a.*,u.name uname,g.name gname,g.thumbnail,d.name dname,d.phone,d.address')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array('-1' => '取消订单', '0' => '待支付', '1' => '已支付', '2' => '已发货', '3' => '已收货', '4' => '退款');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text']  = $status[$item['status']];
                $item['paytype_text'] = $item['paytype_text'] == 1 ? '支付宝' : '微信';
                if (empty($item['paytime'])) {
                    $item['paytime'] = date('Y-m-d H:i:s', $item['paytime']);
                }
                if (empty($item['finishtime'])) {
                    $item['finishtime'] = date('Y-m-d H:i:s', $item['finishtime']);
                }
                if (empty($item['canceltime'])) {
                    $item['canceltime'] = date('Y-m-d H:i:s', $item['canceltime']);
                }
                if (empty($item['delivertime'])) {
                    $item['delivertime'] = date('Y-m-d H:i:s', $item['delivertime']);
                }
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('goods g', 'a.gid=g.id', 'left')
            ->join('delivery_address d', 'a.addressid=d.id', 'left')
            ->field('a.*,u.name uname,g.name gname,g.thumbnail,d.name dname,d.phone,d.address')
            ->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $status               = array('-1' => '取消订单', '0' => '待支付', '1' => '已支付', '2' => '已发货', '3' => '已收货', '4' => '退款');
            $item                 = $item->toArray();
            $item['status_text']  = $status[$item['status']];
            $item['paytype_text'] = $item['paytype_text'] == 1 ? '支付宝' : '微信';
            if (empty($item['paytime'])) {
                $item['paytime'] = date('Y-m-d H:i:s', $item['paytime']);
            }
            if (empty($item['finishtime'])) {
                $item['finishtime'] = date('Y-m-d H:i:s', $item['finishtime']);
            }
            if (empty($item['canceltime'])) {
                $item['canceltime'] = date('Y-m-d H:i:s', $item['canceltime']);
            }
            if (empty($item['delivertime'])) {
                $item['delivertime'] = date('Y-m-d H:i:s', $item['delivertime']);
            }
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}