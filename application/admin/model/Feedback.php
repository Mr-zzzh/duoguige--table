<?php

namespace app\admin\model;

class Feedback extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['f.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['f.content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('f')
            ->join('user u', 'f.uid=u.id', 'left')
            ->field('f.*,u.name uname')->where($map)
            ->order('f.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
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
}