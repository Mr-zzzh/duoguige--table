<?php

namespace app\mobile\model;

class Company extends Common {

    public function AddOne($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        /* if ($this->where('uid', $member['id'])->value('id')) {
             show_json(0, '资料已提交,请不要重复提交');
         }*/
        $data = array(
            'uid'          => $member['id'],
            'company_name' => trim($params['company_name']),
            'phone'        => trim($params['phone']),
            'name'         => trim($params['name']),
            'area'         => trim($params['area']),
            'address'      => trim($params['address']),
            'number'       => intval($params['number']),
            'brand'        => trim($params['brand']),
            'image'        => trim($params['image']),
            'createtime'   => time(),
        );
        if (empty($data['company_name'])) {
            show_json(0, '公司名称不能为空');
        }
        if (empty($data['phone'])) {
            show_json(0, '联系电话不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '公司法人姓名不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '公司地址不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
        if (empty($data['number'])) {
            show_json(0, '电梯数量不能为空');
        }
        if (empty($data['brand'])) {
            show_json(0, '电梯品牌不能为空');
        }
        if (empty($data['image'])) {
            show_json(0, '营业执照不能为空');
        }
        $user['status']          = 0;
        $user['presuppose_type'] = 3;
        db('user')->where('id', $member['id'])->update($user);
        if ($this->where('uid', $member['id'])->select()) {
            $this->where('uid', $member['id'])->delete();
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            if (db('inform')->where(array('uid' => $member['id'], 'type' => 1))->select()) {
                db('inform')->where(array('uid' => $member['id'], 'type' => 1))->delete();
            }
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function EditOne($params, $id) {
        global $member;
        $data = array(
            'uid'          => $member['id'],
            'company_name' => trim($params['company_name']),
            'phone'        => trim($params['phone']),
            'name'         => trim($params['name']),
            'area'         => trim($params['area']),
            'address'      => trim($params['address']),
            'number'       => intval($params['number']),
            'brand'        => trim($params['brand']),
            'image'        => trim($params['image']),
            'createtime'   => time(),
        );
        if (empty($data['company_name'])) {
            show_json(0, '公司名称不能为空');
        }
        if (empty($data['phone'])) {
            show_json(0, '联系电话不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '公司法人姓名不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '公司地址不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
        if (empty($data['number'])) {
            show_json(0, '电梯数量不能为空');
        }
        if (empty($data['brand'])) {
            show_json(0, '电梯品牌不能为空');
        }
        if (empty($data['image'])) {
            show_json(0, '营业执照不能为空');
        }
        $user['status']          = 0;
        $user['presuppose_type'] = 3;
        db('user')->where('id', $member['id'])->update($user);
        if ($this->save($data, array('id' => $id)) !== false) {
            $iid = db('inform')->where(array('checkid' => $id, 'type' => 1, 'is_click' => 1))->order('createtime desc')->limit(1)->value('id');
            if ($iid > 0) {
                db('inform')->where('id', $iid)->update(array('is_click' => 2));
            }
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