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
            ->field('a.id,a.title,a.thumb,count(b.id) number')
            ->where($map)->group('a.id')->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        $data = array(
            'uid'        => $member['id'],
            'title'      => trim($params['title']),
            'type'       => 1,
            'createtime' => time(),
        );
        if (empty($data['title'])) {
            show_json(0, '问题不能为空');
        }
        if (!empty($params['thumb'])) {
            if (is_array($params['thumb'])) {
                $data['thumb'] = implode(',', trim($params['thumb']));
            } else {
                $data['thumb'] = trim($params['thumb']);
            }
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