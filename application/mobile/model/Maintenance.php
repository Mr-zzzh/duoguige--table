<?php

namespace app\mobile\model;

class Maintenance extends Common {

    public function GetAll($params) {
        global $member;
        $map = array();
        if (!empty($params['type'])) {
            if (intval($params['type']) == 1) {
                $map['m.status'] = 0;
            } else {
                $map['m.status'] = array('in', '1,2');
            }
        }
        if (empty($params['genre'])) {
            $map['a.genre'] = 1;
        } else {
            $map['a.genre'] = intval($params['genre']);
        }
        $map['m.uid'] = $member['id'];
        $list         = $this->alias('m')
            ->join('evaluate e', 'e.mid=m.id', 'left')
            ->join('user u', 'u.id=m.receive_id', 'left')
            ->field('m.id,m.brand,m.model,m.floor_number,m.type,m.company,m.city,m.area,m.address,m.status,m.createtime,m.receive_id,u.name as receive_name,count(e.id) as evaluate')
            ->where($map)->group('m.id')->order('m.createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['address']    = city_name($item['city']) . city_name($item['area']) . $item['address'];
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'uid'          => $member['id'],
            'brand'        => trim($params['brand']),
            'model'        => trim($params['model']),
            'floor_number' => intval($params['floor_number']),
            'type'         => trim($params['type']),
            'company'      => trim($params['company']),
            'province'     => intval($params['province']),
            'city'         => intval($params['city']),
            'area'         => intval($params['area']),
            'address'      => trim($params['address']),
            'genre'        => intval($params['genre']),
            'status'       => 0,
            'createtime'   => time(),
        );
        if (empty($data['brand'])) {
            show_json(0, '电梯品牌不能为空');
        }
        if (empty($data['model'])) {
            show_json(0, '电梯型号不能为空');
        }
        if (empty($data['floor_number'])) {
            show_json(0, '楼层数不能为空');
        }
        if (empty($data['type'])) {
            show_json(0, '维修类型不能为空');
        }
        if (empty($data['company'])) {
            show_json(0, '单位名称不能为空');
        }
        if (empty($data['province'])) {
            show_json(0, '省不能为空');
        }
        if (empty($data['city'])) {
            show_json(0, '市不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '区不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
        if (empty($data['genre'])) {
            show_json(0, '维保单类型不能为空');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
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
            'uid'                  => intval($params['uid']),
            'brand'                => trim($params['brand']),
            'model'                => trim($params['model']),
            'floor_number'         => intval($params['floor_number']),
            'type'                 => trim($params['type']),
            'company'              => trim($params['company']),
            'address'              => trim($params['address']),
            'status'               => intval($params['status']),
            'star'                 => intval($params['star']),
            'evaluate'             => trim($params['evaluate']),
            'complain'             => trim($params['complain']),
            'complain_image'       => trim($params['complain_image']),
            'checktime'            => intval($params['checktime']),
            'canceltime'           => intval($params['canceltime']),
            'finishtime'           => intval($params['finishtime']),
            'evaluate_time'        => intval($params['evaluate_time']),
            'complain_time'        => intval($params['complain_time']),
            'complain_finish_time' => intval($params['complain_finish_time']),
        );
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function StatusEdit($params) {
        global $member;
        $id = intval($params['id']);
        if (empty($id)) {
            show_json(0, '请传维保单id');
        }
        $data = array(
            'status' => trim($params['status']),
        );
        if ($data['status'] == -1) {
            $data['canceltime'] = time();
        } elseif ($data['status'] == 4) {
            $data['finishtime'] = time();
        } else {
            show_json(0, '请传正确状态');
        }
        if (!$this->where(array('id' => $id, 'uid' => $member['id']))->value('id')) {
            show_json(0, '您不能对此维保单进行操作');
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '操作成功');
        } else {
            show_json(0, '操作失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('user u1', 'a.uid=u1.id', 'left')
            ->join('company c', 'a.uid=c.uid', 'left')
            ->join('user u2', 'a.receive_id=u2.id', 'left')
            ->join('technician t', 'a.receive_id=t.uid', 'left')
            ->field('a.id,a.brand,a.model,a.floor_number,a.type,a.company,a.city,a.area,a.address,a.status,a.receive_id,a.receive_time,u1.name,u1.avatar,c.company_name,u2.phone receive_phone,u2.avatar receive_avatar,t.name receive_name,t.company_name receive_company')
            ->where('a.id', $id)
            ->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item            = $item->toArray();
            $item['address'] = city_name($item['city']) . city_name($item['area']) . $item['address'];
            $plan            = db('plan')->where('mid', $id)->field('plan,createtime')->order('createtime desc')->select();
            if (!empty($item['receive_time'])) {
                array_push($plan, array('plan' => '已接单', 'createtime' => $item['receive_time']));
                foreach ($plan as &$v) {
                    $v['createtime'] = date('Y-n-d H:i', $v['createtime']);
                }
                unset($v);
            }
            $complaint = db('complaint')->where('mid', $id)->order('createtime desc')->limit(1)->find();
            if (!empty($complaint['thumb'])) {
                $complaint['thumb'] = explode(',', $complaint['thumb']);
            }
        }
        show_json(1, array('data' => $item, 'plan' => $plan, 'complaint' => $complaint));
    }

    public function Evaluate($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'uid'        => $member['id'],
            'mid'        => intval($params['id']),
            'start'      => intval($params['start']),
            'content'    => trim($params['content']),
            'createtime' => time(),
        );
        if (empty($data['mid'])) {
            show_json(0, '维保单id不能为空');
        }
        if (empty($data['start'])) {
            show_json(0, '星星数量不能为空');
        }
        if (empty($data['content'])) {
            show_json(0, '评价内容不能为空');
        }
        if (!$this->where(array('id' => $data['mid'], 'uid' => $member['id'], 'status' => array('>', 3)))->value('id')) {
            show_json(0, '此维保单不能评价');
        }
        if (db('evaluate')->where(array('mid' => $data['mid'], 'uid' => $member['id']))->value('id')) {
            show_json(0, '您已评价过此维保单');
        }
        if (db('evaluate')->insert($data)) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function Complaint($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'uid'        => $member['id'],
            'mid'        => intval($params['id']),
            'content'    => trim($params['content']),
            'createtime' => time(),
        );
        if (empty($data['mid'])) {
            show_json(0, '维保单id不能为空');
        }
        if (empty($data['content'])) {
            show_json(0, '投诉内容不能为空');
        }
        if (!empty($params['thumb'])) {
            if (is_array($params['thumb'])) {
                $data['thumb'] = implode(',', trim($params['thumb']));
            } else {
                $data['thumb'] = trim($params['thumb']);
            }
        }
        if (!$this->where(array('id' => $data['mid'], 'uid' => $member['id'], 'status' => array('>', 3)))->value('id')) {
            show_json(0, '此维保单不能投诉');
        }
        if (db('complaint')->insert($data)) {
            $this->where('id', $data['mid'])->update(array('status' => 5));
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function AllEvaluate($params) {
        $id = intval($params['id']);
        if (empty($id)) {
            show_json(0, '请传维修师傅id');
        }
        $map                 = array();
        $map['m.receive_id'] = $id;
        $list                = db('evaluate')->alias('e')
            ->join('maintenance m', 'e.mid=m.id', 'left')
            ->join('user u', 'u.id=m.uid', 'left')
            ->field('m.brand,m.model,m.floor_number,m.type,m.company,u.name,u.avatar,e.start,e.content,e.createtime')
            ->where($map)->order('e.createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function TaskHall($params) {
        global $member;
        if ($member['type'] != 2) {
            show_json(0, '无查看权限');
        }
        $map = array();
        if (!empty($params['city'])) {
            $map['m.city'] = intval($params['city']);
        }
        if (!empty($params['area'])) {
            $map['m.area'] = intval($params['area']);
        }
        $map['m.status'] = 1;
        $list            = $this->alias('m')
            ->field('m.id,m.brand,m.model,m.floor_number,m.type,m.company,m.city,m.area,m.address,m.createtime')
            ->where($map)->group('m.id')->order('m.createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['address']    = city_name($item['city']) . city_name($item['area']) . $item['address'];
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function ReceiveTask($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $id = trim($params['id']);
        if (empty($id)) {
            show_json(0, '请传维保单id');
        }
        if (intval($params['type']) == 1) {
            if (!$this->where(array('id' => $id, 'status' => 1))->find()) {
                show_json(0, '此维保单已被接取');
            }
            $data['receive_id']   = $member['id'];
            $data['receive_time'] = time();
            $data['status']       = 3;
            if ($this->save($data, array('id' => $id)) !== false) {
                show_json(1, '操作成功');
            } else {
                show_json(0, '操作失败');
            }
        } elseif (intval($params['type']) == 2) {
            if (!$this->where(array('id' => $id, 'receive_id' => $member['id']))->find()) {
                show_json(0, '您不能操作此维保单');
            }
            $data['status'] = 6;
            if ($this->save($data, array('id' => $id)) !== false) {
                show_json(1, '操作成功');
            } else {
                show_json(0, '操作失败');
            }
        } else {
            show_json(0, '类型不正确');
        }

    }

    public function MyTask($params) {
        global $member;
        $map               = array();
        $map['receive_id'] = $member['id'];
        if (intval($params['type']) == 2) {
            $map['status'] = array('>', 4);
        }
        if (!empty($params['time'])) {
            $days                = date('t', strtotime($params['time']));
            $map['receive_time'] = array('between', strtotime($params['time'] . '-01 00:00:00') . ',' . strtotime($params['time'] . '-' . $days . ' 23:59:59'));
        }
        $list = $this->field('id,brand,model,floor_number,type,company,city,area,address,status,createtime')
            ->where($map)->order('createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['address']    = city_name($item['city']) . city_name($item['area']) . $item['address'];
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
                if (intval($params['type']) != 2) {
                    $item['evaluate'] = db('evaluate')->alias('a')
                        ->join('user u', 'u.id=a.uid', 'left')
                        ->field('u.name,u.avatar,a.start,a.content,a.createtime')
                        ->where('a.mid', $item['id'])->find();
                    if (!empty($item['evaluate'])) {
                        $item['evaluate']['createtime'] = date('Y-m-d', $item['evaluate']['createtime']);
                    }
                }
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function TaskDetail($params) {
        $id = intval($params['id']);
        if (empty($id)) {
            show_json(0, '请传维保单id');
        }
        $item = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('company c', 'a.uid=c.uid', 'left')
            ->field('a.id,a.brand,a.model,a.floor_number,a.type,a.company,a.city,a.area,a.address,u.phone,u.name,u.avatar,c.company_name')
            ->where('a.id', $id)
            ->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item            = $item->toArray();
            $item['address'] = city_name($item['city']) . city_name($item['area']) . $item['address'];
            $item['plan']    = db('plan')->where('mid', $id)->order('createtime desc')->limit(1)->value('plan');
        }
        show_json(1, $item);
    }

    public function Plan($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'mid'  => trim($params['id']),
            'plan' => trim($params['plan']),
        );
        if (empty($data['mid'])) {
            show_json(0, '请传维保单id');
        }
        if (empty($data['plan'])) {
            show_json(0, '请传维保单进度');
        }
        if (db('plan')->where($data)->value('id')) {
            show_json(1, '操作成功');
        }
        $data['createtime'] = time();
        if (db('plan')->insert($data)) {
            show_json(1, '操作成功');
        } else {
            show_json(0, '操作失败');
        }
    }

    public function TcomplaintDetail($params) {
        $id = intval($params['id']);
        if (empty($id)) {
            show_json(0, '请传维保单id');
        }
        $item = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('company c', 'a.uid=c.uid', 'left')
            ->join('complaint cm', 'a.id=cm.mid', 'left')
            ->field('a.id,a.brand,a.model,a.floor_number,a.type,a.company,a.city,a.area,a.address,a.status,u.phone,u.name,u.avatar,c.company_name')
            ->where('a.id', $id)
            ->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item            = $item->toArray();
            $item['address'] = city_name($item['city']) . city_name($item['area']) . $item['address'];
            $complaint       = db('complaint')->where('mid', $id)->order('createtime desc')->limit(1)->find();
            if (!empty($complaint['thumb'])) {
                $complaint['thumb'] = explode(',', $complaint['thumb']);
            }
            $item['content'] = $complaint['content'];
            $item['thumb']   = $complaint['thumb'];
        }
        show_json(1, $item);
    }

}