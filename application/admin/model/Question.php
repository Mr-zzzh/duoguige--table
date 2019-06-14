<?php

namespace app\admin\model;

class Question extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['a.title'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['a.type'] = 1;
        $list          = $this->alias('a')
            ->join('user u', 'a.uid=u.id')
            ->field('a.id,a.uid,a.title,a.thumb,a.createtime,u.name uname')->where($map)
            ->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function GetOne($params) {
        if (empty($params['id'])) {
            show_json(0, '请传问题id');
        }
        $list = db('answer')->alias('a')
            ->join('user u', 'a.uid=u.id')
            ->field('a.*,u.name uname')->where('a.qid', intval($params['id']))
            ->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array(1 => '显示', 2 => '隐藏');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text'] = $status[$item['status']];
                $item['createtime']  = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function DelAnswer($id) {
        if (db('answer')->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function EditStatus($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id传输错误');
        }
        if (empty($params['status'])) {
            show_json('请传审核状态');
        }
        $data['status'] = intval($params['status']);
        if (db('answer')->where('id', intval($params['id']))->update($data) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '修改成功');
        } else {
            show_json(0, '修改失败');
        }
    }

}