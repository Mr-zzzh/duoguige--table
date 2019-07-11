<?php

namespace app\mobile\model;

class Inform extends Common {

    //未读消息数量
    public function unreadnum() {
        global $member;
        $total = intval($this->where(array('uid' => $member['id'], 'read' => 0))->count('id'));
        return $total;
    }

    public function GetAll($params) {
        global $member;
        $map        = array();
        $map['uid'] = 1;
        $list       = $this->field('id,title,read,createtime')
            ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
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
        $user['status'] = 0;
        $user['type']   = 3;
        db('user')->where('id', $member['id'])->update($user);
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