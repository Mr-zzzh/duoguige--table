<?php

namespace app\mobile\model;

class DeliveryAddress extends Common {

    public function GetAll() {
        global $member;
        $list = $this->where('uid', $member['id'])->select()->toArray();
        if (!empty($list)) {
            foreach ($list as $k => &$item) {
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
            'uid'        => $member['id'],
            'name'       => trim($params['name']),
            'phone'      => trim($params['phone']),
            'area'       => trim($params['area']),
            'address'    => trim($params['address']),
            'default'    => intval($params['default']),
            'createtime' => time(),
        );
        if (empty($data['name'])) {
            show_json('0', '收货人姓名不能为空');
        }
        if (empty($data['phone'])) {
            show_json('0', '收货人手机不能为空');
        }
        if (!is_mobilenumber($data['phone'])) {
            show_json('0', '请传正确手机号');
        }
        if (empty($data['area'])) {
            show_json('0', '收货地区不能为空');
        }
        if (empty($data['address'])) {
            show_json('0', '收货地址不能为空');
        }
        if ($data['default'] == 1) {
            $this->where(array('uid' => $member['id']))->update(array('default' => 0));
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
        global $member;
        $data = array(
            'name'    => trim($params['name']),
            'phone'   => trim($params['phone']),
            'area'    => trim($params['area']),
            'address' => trim($params['address']),
            'default' => intval($params['default']),
        );
        if (empty($data['name'])) {
            show_json('0', '收货人姓名不能为空');
        }
        if (empty($data['phone'])) {
            show_json('0', '收货人手机不能为空');
        }
        if (!is_mobilenumber($data['phone'])) {
            show_json('0', '请传正确手机号');
        }
        if (empty($data['area'])) {
            show_json('0', '收货地区不能为空');
        }
        if (empty($data['address'])) {
            show_json('0', '收货地址不能为空');
        }
        if ($data['default'] == 1) {
            $this->where(array('uid' => $member['id']))->update(array('default' => 0));
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
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}