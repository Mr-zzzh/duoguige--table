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
            ->field('a.*,u.name uname,g.name gname,g.thumbnail,d.name dname,d.phone,d.area,d.address')
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

    public function Deliver($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id传输错误');
        }
        if (empty($params['expresscom'])) {
            show_json('快递公司');
        }
        if (empty($params['expresssn'])) {
            show_json('快递单号');
        }
        $data['expresscom']  = trim($params['expresscom']);
        $data['expresssn']   = trim($params['expresssn']);
        $data['delivertime'] = time();
        if ($this->save($data, array('id' => intval($params['id']))) != false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '发货成功');
        } else {
            show_json(0, '发货失败');
        }
    }

    public function TrendChart($params) {
        if (empty($params['starttime'])) {
            show_json(0, '请传开始时间');
        }
        if (empty($params['endtime'])) {
            show_json(0, '请传结束时间');
        }
        $time      = array();
        $starttime = strtotime($params['starttime']);
        $endtime   = $params['endtime'];
        $time[]    = date('Y-m-d', $starttime);
        while (($starttime = strtotime('+1 day', $starttime)) <= strtotime($endtime)) {
            $time[] = date('Y-m-d', $starttime); // 取得递增月;
        }
        $order  = array();       //下单
        $order1 = array();      //付款
        foreach ($time as &$v) {
            $time1    = strtotime($v . ' 00:00:00');
            $time2    = strtotime($v . ' 23:59:59');
            $order[]  = $this->where(array('createtime' => array('between', $time1, $time2)))->count('id');
            $order1[] = $this->where(array('paytime' => array('between', $time1, $time2)))->count('id');
        }
        unset($v);
        $legend = array('下单', '付款');
        $series = array();
        foreach ($legend as $k1 => &$v1) {
            $series[$k1]['name']  = $v1;
            $series[$k1]['type']  = 'line';
            $series[$k1]['stack'] = '总量';
            $series[$k1]['data']  = $k1 == 1 ? $order1 : $order;
        }
        unset($v1);
        $list['legend'] = $legend;
        $list['series'] = $series;
        $list['time']   = $time;
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
            ->field('a.*,u.name uname,g.name gname,g.thumbnail,d.name dname,d.phone,a.area,d.address')
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