<?php

namespace app\admin\model;

class News extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['title|content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->where($map)->field('id,title,thumb,view_number,like_number,sort,status,createtime')->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
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
            'title'       => trim($params['title']),
            'thumb'       => trim($params['thumb']),
            'content'     => trim($params['content']),
            'view_number' => intval($params['view_number']),
            'like_number' => intval($params['like_number']),
            'sort'        => intval($params['sort']),
            'status'      => intval($params['status']),
            'createtime'  => time(),
        );
        if (empty($data['title'])) {
            show_json(0, '请传标题');
        }
        if (empty($data['thumb'])) {
            show_json(0, '请传图片');
        }
        if (empty($data['content'])) {
            show_json(0, '请传内容详情');
        }
        if (empty($data['sort'])) {
            show_json(0, '请传序号');
        }
        if (empty($data['status'])) {
            $data['status'] = 1;
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
            'title'       => trim($params['title']),
            'thumb'       => trim($params['thumb']),
            'content'     => trim($params['content']),
            'view_number' => intval($params['view_number']),
            'like_number' => intval($params['like_number']),
            'sort'        => intval($params['sort']),
            'status'      => intval($params['status']),
        );
        if (empty($data['title'])) {
            show_json(0, '请传标题');
        }
        if (empty($data['thumb'])) {
            show_json(0, '请传图片');
        }
        if (empty($data['content'])) {
            show_json(0, '请传内容详情');
        }
        if (empty($data['sort'])) {
            show_json(0, '请传序号');
        }
        if (empty($data['status'])) {
            $data['status'] = 1;
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