<?php

namespace app\admin\model;

class Banner extends Common {

    public function GetAll($params) {
        $list = $this->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['status_text'] = $item['status'] == 1 ? '显示' : '不显示';
                $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'url'        => trim($params['url']),
            'jumpurl'    => trim($params['jumpurl']),
            'sort'       => intval($params['sort']),
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        if (empty($data['url'])) {
            show_json(0, '请传图片url');
        }
        if (empty($data['sort'])) {
            show_json(0, '请传序号');
        }
        if (empty($data['status'])) {
            $data['status'] == 1;
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

    public function EditOne($params, $id) {
        $data = array(
            'url'     => trim($params['url']),
            'jumpurl' => trim($params['jumpurl']),
            'sort'    => intval($params['sort']),
            'status'  => intval($params['status']),
        );
        if (empty($data['url'])) {
            show_json(0, '请传图片url');
        }
        if (empty($data['sort'])) {
            show_json(0, '请传序号');
        }
        if (empty($data['status'])) {
            $data['status'] == 1;
        }
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
            $item                = $item->toArray();
            $item['status_text'] = $item['status'] == 1 ? '显示' : '不显示';
            $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}