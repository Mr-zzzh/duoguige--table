<?php

namespace app\mobile\model;

class User extends Common {

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

    //用户登录
    public function login($token = '') {
        $map      = array();
        $password = '';
        if (!empty($token)) {
            $map['token'] = $token;
        } else {
            $params       = Request::instance()->param();
            $mobile       = trim($params['phone']);
            $password     = trim($params['password']);
            $map['phone'] = $mobile;
        }
        $info = $this->where($map)->find();
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
        if ($info['normal'] == 2) {
            $this->error = '该账号已禁用!';
            return false;
        }
        if ($info['type'] == 1) {
            $info['identity'] = '普通用户';
        } elseif ($info['type'] == 2) {
            $info['identity'] = '技术大师';
        } elseif ($info['type'] == 3) {
            $info['identity'] = '物业公司';
        }
        if (empty($token)) {
            $rand          = random(8);
            $info['token'] = md5($rand . $info['id'] . $info['phone'] . time());
            $this->where(array('id' => $info['id']))->update(array('token' => $info['token']));
        }
        $info['createtime'] = date('Y-m-d H:i:s', $info['createtime']);
        unset($info['salt'], $info['password']);
        return $info;
    }

}