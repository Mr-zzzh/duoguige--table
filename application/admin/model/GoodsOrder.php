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
        if (!empty($params['paytype'])) {
            $map['a.paytype'] = intval($params['paytype']);
        }
        if (!empty($params['keyword'])) {
            $map['a.ordersn'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['a.status'] = array('>=', 1);
        $list            = $this->alias('a')
            ->join('user u', 'a.uid = u.id', 'left')
            ->join('goods g', 'a.gid = g.id', 'left')
            ->join('delivery_address d', 'a.addressid = d.id', 'left')
            ->field('a .*,u.name uname,g.name gname,g .thumbnail,d.name dname,d.phone,d.area,d.address')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array(' - 1' => '取消订单', '0' => '待支付', '1' => '已支付', '2' => '已发货', '3' => '已收货');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text']  = $status[$item['status']];
                $item['paytype_text'] = $item['paytype'] == 1 ? '支付宝' : '微信';
                if (empty($item['paytime'])) {
                    $item['paytime'] = date('Y - m - d H:i:s', $item['paytime']);
                }
                if (empty($item['finishtime'])) {
                    $item['finishtime'] = date('Y - m - d H:i:s', $item['finishtime']);
                }
                if (empty($item['canceltime'])) {
                    $item['canceltime'] = date('Y - m - d H:i:s', $item['canceltime']);
                }
                if (empty($item['delivertime'])) {
                    $item['delivertime'] = date('Y - m - d H:i:s', $item['delivertime']);
                }
                $item['createtime'] = date('Y - m - d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        $list['number'] = $this->alias('a')->where($map)->count('a . id');
        $list['money']  = $this->alias('a')->where($map)->sum('a . money');
        show_json(1, $list);
    }

    public function Deliver($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id传输错误');
        }
        $data['status']      = 2;
        $data['delivertime'] = time();
        if ($this->save($data, array('id' => intval($params['id']))) != false) {
            //logs('编辑 ??,ID:' . $id, 3);
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
        $time[]    = date('Y - m - d', $starttime);
        while (($starttime = strtotime(' + 1 day', $starttime)) <= strtotime($endtime)) {
            $time[] = date('Y - m - d', $starttime); // 取得递增月;
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

    public function Summarize() {
        //今日
        $list['today']['turnover'] = db('goods_order')->whereTime('paytime', 'today')->count('id');
        $list['today']['volume']   = db('goods_order')->whereTime('createtime', 'today')->count('id');
        $list['today']['number']   = db('goods_order')->whereTime('paytime', 'today')->sum('money');
        $list['today']['number1']  = db('goods_order')->whereTime('createtime', 'today')->sum('money');
        $member1                   = db('goods_order')->whereTime('paytime', 'today')->group('uid')->count('id');
        if ($member1 > 0) {
            $list['today']['average'] = round($list['today']['number'] / $member1, 2);
        } else {
            $list['today']['average'] = 0;
        }
        //昨日
        $list['yesterday']['turnover'] = db('goods_order')->whereTime('paytime', 'yesterday')->count('id');
        $list['yesterday']['volume']   = db('goods_order')->whereTime('createtime', 'yesterday')->count('id');
        $list['yesterday']['number']   = db('goods_order')->whereTime('paytime', 'yesterday')->sum('money');
        $list['yesterday']['number1']  = db('goods_order')->whereTime('createtime', 'yesterday')->sum('money');
        $member2                       = db('goods_order')->whereTime('paytime', 'yesterday')->group('uid')->count('id');
        if ($member2 > 0) {
            $list['yesterday']['average'] = round($list['yesterday']['number'] / $member2, 2);
        } else {
            $list['yesterday']['average'] = 0;
        }
        //近七日
        $list['seven']['turnover'] = db('goods_order')->whereTime('paytime', ' - 7 days')->count('id');
        $list['seven']['volume']   = db('goods_order')->whereTime('createtime', ' - 7 days')->count('id');
        $list['seven']['number']   = db('goods_order')->whereTime('paytime', ' - 7 days')->sum('money');
        $list['seven']['number1']  = db('goods_order')->whereTime('createtime', ' - 7 days')->sum('money');
        $member3                   = db('goods_order')->whereTime('paytime', ' - 7 days')->group('uid')->count('id');
        if ($member3 > 0) {
            $list['seven']['average'] = round($list['seven']['number'] / $member3, 2);
        } else {
            $list['seven']['average'] = 0;
        }
        //近30天
        $list['month']['turnover'] = db('goods_order')->whereTime('paytime', ' - 30 days')->count('id');
        $list['month']['volume']   = db('goods_order')->whereTime('createtime', ' - 30 days')->count('id');
        $list['month']['number']   = db('goods_order')->whereTime('paytime', ' - 30 days')->sum('money');
        $list['month']['number1']  = db('goods_order')->whereTime('createtime', ' - 30 days')->sum('money');
        $member4                   = db('goods_order')->whereTime('paytime', ' - 30 days')->group('uid')->count('id');
        if ($member4 > 0) {
            $list['month']['average'] = round($list['month']['number'] / $member4, 2);
        } else {
            $list['month']['average'] = 0;
        }
        //近1个月交易走势
        $endtime   = time();
        $starttime = time() - 29 * 24 * 60 * 60;
        $time[]    = date('m-d', $starttime);
        while (($starttime = strtotime(' + 1 day', $starttime)) <= $endtime) {
            $time[] = date('m-d', $starttime); // 取得递增月;
        }
        $number1 = array();       //交易量
        $number2 = array();      //成交量
        $order   = array();      //交易额
        $order1  = array();      //成交额
        $year    = date('Y', time());
        foreach ($time as &$v) {
            $time1     = strtotime($year . '-' . $v . ' 00:00:00');
            $time2     = strtotime($year . '-' . $v . ' 23:59:59');
            $number1[] = db('goods_order')->where(array('createtime' => array('between', $time1 . ',' . $time2)))->count('id');
            $number2[] = db('goods_order')->where(array('paytime' => array('between', $time1 . ',' . $time2)))->count('id');
            $order[]   = db('goods_order')->where(array('createtime' => array('between', $time1 . ',' . $time2)))->sum('money');
            $order1[]  = db('goods_order')->where(array('paytime' => array('between', $time1 . ',' . $time2)))->sum('money');
        }
        unset($v);
        $legend = array('交易量', '成交量', '交易额', '成交额');
        $series = array();
        foreach ($legend as $k1 => &$v1) {
            $series[$k1]['name']  = $v1;
            $series[$k1]['type']  = 'line';
            $series[$k1]['stack'] = '总量';
            if ($k1 == 0) {
                $series[$k1]['data'] = $number1;
            } elseif ($k1 == 1) {
                $series[$k1]['data'] = $number2;
            } elseif ($k1 == 2) {
                $series[$k1]['data'] = $order;
            } elseif ($k1 == 3) {
                $series[$k1]['data'] = $order1;
            }
        }
        unset($v1);
        $list['trend']['legend'] = $legend;
        $list['trend']['series'] = $series;
        $list['trend']['time']   = $time;
        show_json(1, $list);
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除 ??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('user u', 'a . uid = u . id', 'left')
            ->join('goods g', 'a . gid = g . id', 'left')
            ->join('delivery_address d', 'a . addressid = d . id', 'left')
            ->field('a .*,u . name uname,g . name gname,g . thumbnail,d . name dname,d . phone,a . area,d . address')
            ->where('a . id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $status               = array(' - 1' => '取消订单', '0' => '待支付', '1' => '已支付', '2' => '已发货', '3' => '已收货');
            $item                 = $item->toArray();
            $item['status_text']  = $status[$item['status']];
            $item['paytype_text'] = $item['paytype_text'] == 1 ? '支付宝' : '微信';
            if (empty($item['paytime'])) {
                $item['paytime'] = date('Y - m - d H:i:s', $item['paytime']);
            }
            if (empty($item['finishtime'])) {
                $item['finishtime'] = date('Y - m - d H:i:s', $item['finishtime']);
            }
            if (empty($item['canceltime'])) {
                $item['canceltime'] = date('Y - m - d H:i:s', $item['canceltime']);
            }
            if (empty($item['delivertime'])) {
                $item['delivertime'] = date('Y - m - d H:i:s', $item['delivertime']);
            }
            $item['createtime'] = date('Y - m - d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}