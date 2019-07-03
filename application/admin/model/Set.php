<?php

namespace app\admin\model;

class Set extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['keyword'])) {
            $map['content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                //TODO 进行数据处理
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data1 = array(
            'slogan' => trim($params['slogan']),
            'number' => intval($params['number']),
        );
        if (empty($data1['slogan'])) {
            show_json(0, '请传口号');
        }
        if (empty($data1['number'])) {
            show_json(0, '请传编号');
        }
        $data            = array();
        $data['content'] = serialize($data1);
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
            'content' => trim($params['content']),
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
            $item = $item->toArray();
            //TODO 进行数据处理
        }
        show_json(1, $item);
    }

}