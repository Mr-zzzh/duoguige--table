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
            $map['name|phone|avatar|password|salt|intro|token'] = array('LIKE', '%' . trim($params['keyword']) . '%');
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
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id传输错误');
        }
        if (empty($params['status'])) {
            show_json('请传审核状态');
        }
        $data['status']    = intval($params['status']);
        $data['remark']    = trim($params['remark']);
        $data['checktime'] = time();
        if ($this->save($data, array('id' => intval($params['id']))) !== false) {
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