<?php

namespace app\mobile\model;

class Technician extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['name|idcardno|company_name|license_number|company_image|prove_image|technician_image|dimission'] = array('LIKE', '%' . trim($params['keyword']) . '%');
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
        $data = array(
            'uid'              => intval($params['uid']),
            'name'             => trim($params['name']),
            'sex'              => intval($params['sex']),
            'idcardno'         => trim($params['idcardno']),
            'company_name'     => trim($params['company_name']),
            'license_number'   => trim($params['license_number']),
            'company_image'    => trim($params['company_image']),
            'prove_image'      => trim($params['prove_image']),
            'technician_image' => trim($params['technician_image']),
            'dimission'        => trim($params['dimission']),
            'createtime'       => time(),
        );
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
            'uid'              => intval($params['uid']),
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