<?php

namespace app\mobile\model;

class User extends Common {

    public function Register($params) {
        $salt = random(6);
        $data = array(
            'phone'      => trim($params['phone']),
            'name'       => trim($params['phone']),
            'password'   => md5(trim($params['password']) . $salt),
            'salt'       => $salt,
            'avatar'     => request()->domain() . '/uploads/nopic.png',
            'status'     => 0,
            'type'       => 1,
            'createtime' => time(),
            'normal'     => 1,
        );
        if (empty($data['phone'])) {
            show_json('手机号不能为空');
        }
        if (!is_mobilenumber($data['phone'])) {
            show_json('请传正确手机号');
        }
        if (empty($data['password'])) {
            show_json('密码不能为空');
        }
        if ($this->where('phone', $data['phone'])->value('id')) {
            show_json(0, '该手机号已注册');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //show_json(1, '注册成功');
            $info = $this->where('phone', $data['phone'])->find();
            $info = $info->toArray();
            if ($info['normal'] == 2) {
                $this->error = '该账号已禁用!';
                return false;
            }
            if ($info['type'] == 1) {
                $info['identity'] = '普通用户';
                $info['company']  = '无';
            } elseif ($info['type'] == 2) {
                $info['identity'] = '技术大师';
                $info['company']  = db('technician')->where('uid', $info['id'])->value('company_name');
            } elseif ($info['type'] == 3) {
                $info['identity'] = '物业公司';
                $info['company']  = db('company')->where('uid', $info['id'])->value('company_name');
            }
            if (empty($token)) {
                $rand          = random(8);
                $info['token'] = md5($rand . $info['id'] . $info['phone'] . time());
                $this->where(array('id' => $info['id']))->update(array('token' => $info['token']));
            }
            if (empty($info['intro'])) {
                $info['intro'] = '';
            }
            $info['createtime'] = date('Y-m-d H:i:s', $info['createtime']);
            unset($info['salt'], $info['password']);
            show_json(1, $info);
        } else {
            show_json(0, '注册失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array();
        if (!empty($params['name'])) {
            $data['name'] = trim($params['name']);
        }
        if (!empty($params['avatar'])) {
            $data['avatar'] = trim($params['avatar']);
        }
        if (!empty($params['intro'])) {
            $data['intro'] = trim($params['intro']);
        }
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
            $item = $item->toArray();
            if ($item['type'] == 1) {
                $item['identity'] = '普通用户';
                $item['company']  = '无';
            } elseif ($item['type'] == 2) {
                $item['identity'] = '技术大师';
                $item['company']  = db('technician')->where('uid', $item['id'])->value('company_name');
            } elseif ($item['type'] == 3) {
                $item['identity'] = '物业公司';
                $item['company']  = db('company')->where('uid', $item['id'])->value('company_name');
            }
            if (empty($item['intro'])) {
                $item['intro'] = '';
            }
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            unset($item['password']);
            unset($item['salt']);
        }
        show_json(1, $item);
    }

    //用户密码登录
    public function login($token = '') {
        $map      = array();
        $password = '';
        if (!empty($token)) {
            $map['token'] = $token;
        } else {
            $params       = request()->param();
            $mobile       = trim($params['phone']);
            $password     = trim($params['password']);
            $map['phone'] = $mobile;
        }
        $info = $this->where($map)->find();
        if (empty($info)) {
            show_json(-3, '您尚未登录!');
        }
        $info = $info->toArray();
        if (empty($token)) {
            if (md5($password . $info['salt']) != $info['password']) {
                show_json(0, '密码错误,请重新输入!');
            }
        }
        if ($info['normal'] == 2) {
            show_json(0, '该账号已禁用!');
        }
        if ($info['type'] == 1) {
            $info['identity'] = '普通用户';
            $info['company']  = '无';
        } elseif ($info['type'] == 2) {
            $info['identity'] = '技术大师';
            $info['company']  = db('technician')->where('uid', $info['id'])->value('company_name');
        } elseif ($info['type'] == 3) {
            $info['identity'] = '物业公司';
            $info['company']  = db('company')->where('uid', $info['id'])->value('company_name');
        }
        if (empty($token)) {
            $rand          = random(8);
            $info['token'] = md5($rand . $info['id'] . $info['phone'] . time());
            $this->where(array('id' => $info['id']))->update(array('token' => $info['token']));
        }
        if (empty($info['intro'])) {
            $info['intro'] = '';
        }
        $info['createtime'] = date('Y-m-d H:i:s', $info['createtime']);
        unset($info['salt'], $info['password']);
        return $info;
    }

    //用户验证码登录
    public function LoginCode($phone) {
        $info = $this->where('phone', $phone)->find();
        if (empty($info)) {     //注册
            $data = array(
                'phone'      => trim($phone),
                'name'       => trim($phone),
                'status'     => 0,
                'avatar'     => request()->domain() . '/uploads/nopic.png',
                'type'       => 1,
                'createtime' => time(),
                'normal'     => 1,
            );
            $this->insert($data);
            $info = $this->where('phone', $phone)->find();
        }
        $info = $info->toArray();
        if ($info['normal'] == 2) {
            show_json(0, '该账号已禁用!');
        }
        if ($info['type'] == 1) {
            $info['identity'] = '普通用户';
            $info['company']  = '无';
        } elseif ($info['type'] == 2) {
            $info['identity'] = '技术大师';
            $info['company']  = db('technician')->where('uid', $info['id'])->value('company_name');
        } elseif ($info['type'] == 3) {
            $info['identity'] = '物业公司';
            $info['company']  = db('company')->where('uid', $info['id'])->value('company_name');
        }
        if (empty($token)) {
            $rand          = random(8);
            $info['token'] = md5($rand . $info['id'] . $info['phone'] . time());
            $this->where(array('id' => $info['id']))->update(array('token' => $info['token']));
        }
        $info['createtime'] = date('Y-m-d H:i:s', $info['createtime']);
        unset($info['salt'], $info['password']);
        show_json(1, $info);
    }

    public function MyCollect($params) {
        global $member;
        $map          = array();
        $map['a.uid'] = $member['id'];
        $list         = db('download')->alias('a')
            ->join('brand_datum b', 'a.bdid=b.id')
            ->field('a.id,b.id brand_id,b.name,b.size,b.view,b.download')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        show_json(1, $list);
    }

    public function CollectDel($id) {
        global $member;
        if ($id < 1) {
            show_json(0, 'ID数据错误');
        }
        if (db('download')->where(array('uid' => $member['id'], 'id' => $id))->delete()) {
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function MyLike($params) {
        global $member;
        $map                = array();
        $map['uid']         = $member['id'];
        $map['like_number'] = array('>=', 1);
        $list               = db('leave_message')
            ->field('id,nid,content,type,like_number,createtime')
            ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as &$item) {
                $item['day']  = date('Y-m-d', $item['createtime']);
                $item['time'] = date('H:i', $item['createtime']);
                if ($item['type'] == 1) {
                    $item['news_type'] = db('news')->where('id', $item['nid'])->value('type');
                }
                $item['avatar'] = $member['avatar'];
                $item['name']   = $member['name'];
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function TechnicianAdd($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        if (db('technician')->where('uid', $member['id'])->value('id')) {
            show_json(0, '资料已提交,请不要重复提交');
        }
        $data = array(
            'uid'              => $member['id'],
            'name'             => trim($params['name']),
            'sex'              => intval($params['sex']),
            'idcardno'         => trim($params['idcardno']),
            'company_name'     => trim($params['company_name']),
            'license_number'   => trim($params['license_number']),
            'company_image'    => trim($params['company_image']),
            'prove_image'      => trim($params['prove_image']),
            'technician_image' => trim($params['technician_image']),
            'createtime'       => time(),
        );
        if (empty($data['name'])) {
            show_json(0, '姓名不能为空');
        }
        if (empty($data['sex'])) {
            show_json(0, '性别不能为空');
        }
        if (empty($data['idcardno'])) {
            show_json(0, '身份证号码不能为空');
        }
        if (!checkIdCard($data['idcardno'])) {
            show_json(0, '请传正确身份证号码');
        }
        if (empty($data['company_name'])) {
            show_json(0, '公司名称不能为空');
        }
        if (empty($data['license_number'])) {
            show_json(0, '公司营业执照号码不能为空');
        }
        if (empty($data['company_image'])) {
            show_json(0, '公司营业执照照片不能为空');
        }
        if (empty($data['prove_image'])) {
            show_json(0, '在职证明图片不能为空');
        }
        if (empty($data['technician_image'])) {
            show_json(0, '技师证件不能为空');
        }
        $user['status']          = 0;
        $user['presuppose_type'] = 2;
        $this->where('id', $member['id'])->update($user);
        if (db('technician')->insert($data)) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function TechnicianEdit($params) {
        global $member;
        if ($params['id'] < 1) {
            show_json(0, '参数ID错误');
        }
        $id           = intval($params['id']);
        $company_name = db('technician')->where('id', $id)->value('company_name');
        $data         = array(
            'name'             => trim($params['name']),
            'sex'              => intval($params['sex']),
            'idcardno'         => trim($params['idcardno']),
            'company_name'     => trim($params['company_name']),
            'license_number'   => trim($params['license_number']),
            'company_image'    => trim($params['company_image']),
            'prove_image'      => trim($params['prove_image']),
            'technician_image' => trim($params['technician_image']),
            'dimission'        => trim($params['dimission']),
        );
        if (empty($data['name'])) {
            show_json(0, '姓名不能为空');
        }
        if (empty($data['sex'])) {
            show_json(0, '性别不能为空');
        }
        if (empty($data['idcardno'])) {
            show_json(0, '身份证号码不能为空');
        }
        if (empty($data['company_name'])) {
            show_json(0, '公司名称不能为空');
        }
        if (empty($data['license_number'])) {
            show_json(0, '公司营业执照号码不能为空');
        }
        if (empty($data['company_image'])) {
            show_json(0, '公司营业执照照片不能为空');
        }
        if (empty($data['prove_image'])) {
            show_json(0, '在职证明图片不能为空');
        }
        if (empty($data['technician_image'])) {
            show_json(0, '技师证件不能为空');
        }
        if ($data['company_name'] != $company_name) {
            if (empty($data['dimission'])) {
                show_json(0, '离职证明不能为空');
            }
        }
        $user['status']          = 0;
        $user['presuppose_type'] = 2;
        $this->where('id', $member['id'])->update($user);
        if (db('technician')->where('id', $id)->update($data)) {
            $iid = db('inform')->where(array('checkid' => $id, 'type' => 2, 'is_click' => 1))->order('createtime desc')->limit(1)->value('id');
            if ($iid > 0) {
                db('inform')->where('id', $iid)->update(array('is_click' => 2));
            }
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function ApproveDetail() {
        global $member;
        $item = db('technician')->where('uid', $member['id'])->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

    public function PasswordEdit($params) {
        global $member;
        if (empty($params['password'])) {
            show_json('0', '新密码不能为空');
        }
        if (empty($params['confirmpwd'])) {
            show_json('0', '确认密码不能为空');
        }
        if ($params['password'] != $params['confirmpwd']) {
            show_json('0', '两次所传密码不相等');
        }
        $salt = random(6);
        $data = array(
            'password' => md5(trim($params['password']) . $salt),
            'salt'     => $salt,
        );
        if ($this->save($data, array('id' => $member['id'])) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

}