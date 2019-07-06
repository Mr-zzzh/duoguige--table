<?php

namespace app\admin\model;

class Admin extends Common {

    public function Home() {
        $list = array();
        //今日付款金额
        $money = db('goods_order')->where('status', 1)->whereTime('paytime', 'today')->sum('money');
        //今日订单数
        $number = db('goods_order')->whereTime('createtime', 'today')->count('id');
        //今日已付款订单数
        $pay_number = db('goods_order')->where('status', 1)->whereTime('paytime', 'today')->count('id');
        //今日新增会员数
        $member    = db('user')->whereTime('createtime', 'today')->count('id');
        $endtime   = time();
        $starttime = time() - 6 * 24 * 60 * 60;
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");
        $time[]    = date('m-d', $starttime) . '周' . $weekarray[date("w", $starttime)];
        while (($starttime = strtotime('+1 day', $starttime)) <= $endtime) {
            $time[] = date('m-d', $starttime) . '周' . $weekarray[date("w", $starttime)]; // 取得递增月;
        }
        $number1 = array();       //交易量
        $number2 = array();      //成交量
        $order   = array();      //交易额
        $order1  = array();      //成交额
        foreach ($time as &$v) {
            $time1     = strtotime($v . ' 00:00:00');
            $time2     = strtotime($v . ' 23:59:59');
            $number1[] = db('goods_order')->where(array('createtime' => array('between', $time1, $time2)))->count('id');
            $number2[] = db('goods_order')->where(array('paytime' => array('between', $time1, $time2)))->count('id');
            $order[]   = db('goods_order')->where(array('createtime' => array('between', $time1, $time2)))->sum('money');
            $order1[]  = db('goods_order')->where(array('paytime' => array('between', $time1, $time2)))->sum('money');
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
        $list['money']           = $money;
        $list['number']          = $number;
        $list['pay_number']      = $pay_number;
        $list['member']          = $member;
        show_json(1, $list);
    }

    public function Summarize($params) {
        if (empty($params['type']) && $params['type'] == 1) {
            $time = 'today';
        } elseif ($params['type'] == 2) {
            $time = 'yesterday';
        } elseif ($params['type'] == 3) {
            $time = '-7 days';
        } elseif ($params['type'] == 4) {
            $time = 'month';
        } else {
            $time = 'today';
        }
        $list             = array();
        $list['turnover'] = db('goods_order')->where('status', 1)->whereTime('paytime', $time)->count('id');
        $list['volume']   = db('goods_order')->whereTime('createtime', $time)->count('id');
        $list['number']   = db('goods_order')->where('status', 1)->whereTime('paytime', $time)->sum('money');
        $list['number1']  = db('goods_order')->whereTime('createtime', $time)->sum('money');
        $member           = db('goods_order')->whereTime('paytime', $time)->group('uid')->count('id');
        if ($member > 0) {
            $list['average'] = round($list['number'] / $member, 2);
        } else {
            $list['average'] = 0;
        }
        show_json(1, $list);
    }

    public function Market($params) {
        if (empty($params['type']) && $params['type'] == 1) {
            $time = 'today';
        } elseif ($params['type'] == 2) {
            $time = 'yesterday';
        } elseif ($params['type'] == 3) {
            $time = '-7 days';
        } elseif ($params['type'] == 4) {
            $time = 'month';
        } else {
            $time = 'today';
        }
        $list = db('goods_order')->alias('a')
            ->join('goods b', 'a.gid=b.id', 'left')
            ->where('a.status', 1)
            ->whereTime('a.paytime', $time)
            ->field('b.name,count(a.id) as number,sum(a.money) as money')
            ->group('a.gid')->order('money desc')->limit(5)->select();
        show_json(1, $list);
    }

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['a.status'] = intval($params['status']);
        }
        if (!empty($params['keyword'])) {
            $map['a.name|password|salt|token'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('a')
            ->field('a.id,a.name,a.status,a.phone,a.avatar,a.createtime')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
                $item['status_text'] = $item['status'] == 1 ? '启用' : '禁用';
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $rand = random(6);
        $data = array(
            'name'       => trim($params['name']),
            'phone'      => trim($params['phone']),
            'password'   => trim($params['password'] . $rand),
            'salt'       => $rand,
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        if (empty($data['phone'])) {
            show_json(0, '请传手机号');
        }
        if (empty($data['name'])) {
            show_json(0, '请传用户名');
        }
        if (empty($params['password'])) {
            show_json(0, '请传密码');
        }
        if (empty($params['avatar'])) {
            $data['avatar'] = request()->domain() . '/uploads/nopic.png';
        } else {
            $data['avatar'] = trim($params['avatar']);
        }
        if ($this->where('name', $data['name'])->value('id')) {
            show_json(0, '该账号已添加');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    //系统自动注册管理员
    public function register() {
        $id = $this->where(array('name' => 'admin'))->value('id');
        if (!$id) {
            $salt = random(6);
            $data = array(
                'name'       => 'admin',
                'phone'      => 13312345678,
                'avatar'     => request()->domain() . '/uploads/nopic.png',
                'status'     => 1,
                'salt'       => $salt,
                'password'   => md5('123456' . $salt),
                'createtime' => time()
            );
            $res  = $this->save($data);
            return $res;
        } else {
            $this->error = '超管账号已注册!';
            return false;
        }
    }

    //用户登录
    public function login($token = '') {
        $map      = array();
        $password = '';
        if (!empty($token)) {
            $map['a.token'] = $token;
        } else {
            $params        = request()->param();
            $phone         = trim($params['phone']);
            $password      = trim($params['password']);
            $map['a.name'] = $phone;
        }
        $info = $this->alias('a')->field('a.*')
            ->where($map)->find();
        if (empty($info)) {
            $this->error = '未找到用户!';
            return false;
        }
        $info = $info->toArray();
        if (empty($token)) {
            if (md5($password . $info['salt']) != $info['password']) {
                $this->error = '密码错误,请重新输入!';
                return false;
            }
        }
        if (empty($info['status'])) {
            $this->error = '该账户已禁用!';
            return false;
        }
        if (empty($token)) {
            $rand          = random(8);
            $info['token'] = md5($rand . $info['id'] . $info['phone'] . time());
        }
        $this->where(array('id' => $info['id']))->update(array('token' => $info['token']));
        unset($info['salt'], $info['password']);
        return $info;
    }

    public function EditOne($params, $id) {
        $data = array(
            'phone'  => trim($params['phone']),
            'name'   => trim($params['name']),
            'status' => intval($params['status']),
        );
        if (empty($data['phone'])) {
            show_json(0, '请传手机号');
        }
        if (empty($data['name'])) {
            show_json(0, '请传用户名');
        }
        if (empty($params['avatar'])) {
            $data['avatar'] = request()->domain() . '/uploads/nopic.png';
        } else {
            $data['avatar'] = trim($params['avatar']);
        }
        if ($this->where(array('name' => $data['phone'], 'id' => ['<>', $id]))->value('id')) {
            show_json(0, '该账号已存在');
        }
        if (!empty($params['password'])) {
            $data['salt']     = random(6);
            $data['password'] = md5(trim($params['password']) . $data['salt']);
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑 ??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->get(array('id' => $id));
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}