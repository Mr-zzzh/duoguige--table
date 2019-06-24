<?php

namespace app\mobile\model;

class Maintenance extends Common {

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
            $map['brand|model|type|company|address|evaluate|complain|complain_image'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
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