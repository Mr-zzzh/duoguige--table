<?php

namespace app\admin\model;

class User extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['status'] = intval($params['status']);
        }
        if (isset($params['type']) && $params['type'] !== '') {
            $map['type'] = intval($params['type']);
        }
        if (!empty($params['keyword'])) {
            $map['name|phone|intro'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->field('id,name,phone,avatar,intro,status,type,normal,createtime')->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array('0' => '待审', '1' => '通过', '2' => '不通过');
            $type   = array('1' => '普通用户', '2' => '技术大师', '3' => '物业公司');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text'] = $status[$item['status']];
                $item['type_text']   = $type[$item['type']];
                $item['normal_text'] = $item['normal'] == 1 ? '启用' : '禁用';
                $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function Technician($params) {
        $map             = array();
        $map['u.status'] = 1;
        $map['u.type']   = 2;
        $map['u.normal'] = 1;
        if (!empty($params['keyword'])) {
            $map['u.name|u.phone|u.intro'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('u')
            ->join('maintenance m', 'u.id=receive_id', 'left')
            ->field('u.id,u.name,u.phone,count(m.id) as number')
            ->where($map)->group('u.id')->order('u.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['score'] = db('maintenance')->alias('a')
                    ->join('evaluate e', 'a.id=e.mid', 'right')
                    ->where('a.receive_id', $item['id'])->sum('start');
                $item['score'] = intval($item['score']) * 10;
            }
            unset($item);
        }
        $grade = db('grade')->where('status', 1)->find();
        if (empty($grade)) {
            foreach ($list['data'] as $k => &$v) {
                $v['grade'] = '暂未开启等级制度';
            }
            unset($v);
        } else {
            $grade['content'] = unserialize($grade['content']);
            foreach ($list['data'] as $k => &$v1) {
                $a = 0;
                $b = 0;
                foreach ($grade['content'] as $key2 => &$v2) {
                    if ($v1['score'] >= $v2['min_score'] && $v1['score'] < $v2['max_score']) {
                        $a1 = $v2['name'];
                        $a  = $key2;
                    }
                    if ($v1['number'] >= $v2['min_number'] && $v1['number'] < $v2['max_number']) {
                        $b1 = $v2['name'];
                        $b  = $key2;
                    }
                }
                unset($v2);
                if ($a > $b) {
                    $v1['grade'] = $a1;
                } else {
                    $v1['grade'] = $b1;
                }
            }
            unset($v1);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'name'       => trim($params['name']),
            'phone'      => trim($params['phone']),
            'avatar'     => trim($params['avatar']),
            'password'   => trim($params['password']),
            'salt'       => trim($params['salt']),
            'intro'      => trim($params['intro']),
            'status'     => intval($params['status']),
            'type'       => intval($params['type']),
            'token'      => trim($params['token']),
            'createtime' => time(),
        );
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

    public function EditOne($params, $id) {
        $data = array(
            'name'     => trim($params['name']),
            'phone'    => trim($params['phone']),
            'avatar'   => trim($params['avatar']),
            'password' => trim($params['password']),
            'salt'     => trim($params['salt']),
            'intro'    => trim($params['intro']),
            'status'   => intval($params['status']),
            'type'     => intval($params['type']),
            'token'    => trim($params['token']),
        );
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
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
            $status              = array('0' => '待审', '1' => '通过', '2' => '不通过');
            $type                = array('1' => '普通用户', '2' => '技术大师', '3' => '物业公司');
            $item                = $item->toArray();
            $item['status_text'] = $status[$item['status']];
            $item['type_text']   = $type[$item['type']];
            $item['normal_text'] = $item['type'] == 1 ? '启用' : '禁用';
            $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
            unset($item['password']);
            unset($item['salt']);
            unset($item['token']);
            if ($item['type'] == 2) {
                $check = db('technician')->where('uid', $item['id'])->find();
            } elseif ($item['type'] == 3) {
                $check = db('company')->where('uid', $item['id'])->find();
            } else {
                $check = array();
            }
            if (!empty($check)) {
                $check['createtime'] = date('Y-m-d H:i:s', $check['createtime']);
            }
            $item['check'] = $check;
        }
        show_json(1, $item);
    }

    public function EditStatus($params) {
        $id = intval($params['id']);
        if (empty($id) || $id < 1) {
            show_json('id传输错误');
        }
        if (empty($params['status'])) {
            show_json('请传审核状态');
        }
        $data['status']    = intval($params['status']);
        $data['remark']    = trim($params['remark']);
        $data['checktime'] = time();
        if ($this->save($data, array('id' => $id)) !== false) {
            $type1 = $this->where('id', $id)->value('type');
            if ($type1 == 2) {
                $type    = 2;
                $checkid = db('technician')->where('uid', $id)->value('id');
            } else {
                $type    = 1;
                $checkid = db('company')->where('uid', $id)->value('id');
            }
            inform_add($id, $data['status'], $type, $checkid, $data['remark']);
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '审核成功');
        } else {
            show_json(0, '审核失败');
        }
    }

    public function Forbidden($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id传输错误');
        }
        if (empty($params['normal'])) {
            show_json('请传状态');
        }
        $data['normal'] = intval($params['normal']);
        if ($this->save($data, array('id' => intval($params['id']))) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '修改成功');
        } else {
            show_json(0, '修改失败');
        }
    }

}