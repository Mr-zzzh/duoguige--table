<?php

namespace app\admin\model;

class Admin extends Common {

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
            ->field('a.id,a.name,a.status,a.phone,a.createtime')
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
        if ($this->where(array('phone' => $data['phone'], 'id' => ['<>', $id]))->value('id')) {
            show_json(0, '该手机号已存在');
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
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y - m - d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}