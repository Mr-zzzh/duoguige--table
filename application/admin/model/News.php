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
        $list = $this->where($map)->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['type_text']   = $item['type'] == 1 ? '图文' : '视频';
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
            'type'        => trim($params['type']),
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
        if (empty($data['type'])) {
            show_json(0, '请传新闻类型');
        }
        if ($data['type'] == 1) {
            if (empty($params['content'])) {
                show_json(0, '请传内容详情');
            }
            $data['content'] = trim($params['content']);
        } else {
            if (empty($params['video'])) {
                show_json(0, '请传视频链接');
            }
            $data['video'] = trim($params['video']);
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
            'type'        => trim($params['type']),
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
        if (empty($data['type'])) {
            show_json(0, '请传新闻类型');
        }
        if ($data['type'] == 1) {
            if (empty($params['content'])) {
                show_json(0, '请传内容详情');
            }
            $data['content'] = trim($params['content']);
        } else {
            if (empty($params['video'])) {
                show_json(0, '请传视频链接');
            }
            $data['video'] = trim($params['video']);
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
            $item['type_text']   = $item['type'] == 1 ? '图文' : '视频';
            $item['status_text'] = $item['status'] == 1 ? '显示' : '不显示';
            $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

    public function Comment($params) {
        $map = array();
        if (empty($params['nid'])) {
            show_json(0, '请传新闻id');
        }
        $map['a.type'] = 1;
        $map['a.nid']  = intval($params['nid']);
        $list          = db('leave_message')->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->field('a.id,a.content,a.like_number,a.createtime,u.name,u.avatar')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function CommentDel($id) {
        if (db('leave_message')->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

}