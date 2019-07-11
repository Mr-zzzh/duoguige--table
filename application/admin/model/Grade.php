<?php

namespace app\admin\model;

class Grade extends Common {

    public function GetAll($params) {
        $list = $this->order('createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['content']    = unserialize($item['content']);
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'name'       => trim($params['name']),
            'content'    => $params['content'],
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        if (empty($data['name'])) {
            show_json(0, '请传等级名称');
        }
        if (empty($data['content'])) {
            show_json(0, '晋级条件');
        }
        $data['content'] = serialize($data['content']);
        if (empty($data['status'])) {
            $data['status'] == 2;
        }
        if (intval($data['status']) == 1) {
            $this->where('status', 1)->update(array('status' => 2));
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
            'name'       => trim($params['name']),
            'content'    => $params['content'],
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        if (empty($data['name'])) {
            show_json(0, '请传等级名称');
        }
        if (empty($data['content'])) {
            show_json(0, '晋级条件');
        }
        $data['content'] = serialize($data['content']);
        if (empty($data['status'])) {
            $data['status'] == 2;
        }
        if (intval($data['status']) == 1) {
            $this->where('status', 1)->update(array('status' => 2));
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id, $status) {
        if ($status == 1) {
            $this->where('status', 1)->update(array('status' => 2));
        }
        if ($this->save(array('status' => $status), array('id' => $id)) !== false) {
            show_json(1, '切换成功');
        } else {
            show_json(0, '切换失败');
        }
    }

}