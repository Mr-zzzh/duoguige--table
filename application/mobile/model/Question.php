<?php

namespace app\mobile\model;

class Question extends Common {

    public function GetAll($params) {
        $map           = array();
        $map['a.type'] = 1;
        if (!empty($params['keyword'])) {
            $map['a.title'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('a')
            ->join('answer b', 'a.id=b.qid', 'left')
            ->field('a.id,a.title,a.thumb,count(b.id) as number')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (empty($item['id'])) {
                    unset($list['data'][$k]);
                }
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'uid'        => intval($params['uid']),
            'title'      => trim($params['title']),
            'thumb'      => trim($params['thumb']),
            'type'       => intval($params['type']),
            'master_id'  => intval($params['master_id']),
            'createtime' => time(),
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
            'uid'       => intval($params['uid']),
            'title'     => trim($params['title']),
            'thumb'     => trim($params['thumb']),
            'type'      => intval($params['type']),
            'master_id' => intval($params['master_id']),
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