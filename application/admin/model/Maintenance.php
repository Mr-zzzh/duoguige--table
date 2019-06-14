<?php

namespace app\admin\model;

class Maintenance extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['m.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['m.status'] = intval($params['status']);
        }
        if (isset($params['genre']) && $params['genre'] !== '') {
            $map['m.genre'] = intval($params['genre']);
        }
        if (!empty($params['keyword'])) {
            $map['m.brand|m.model|m.company|m.address'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('m')
            ->join('user u1', 'm.uid=u1.id', 'left')
            ->join('user u2', 'm.receive_id=u2.id', 'left')
            ->field('m.*,u1.name uname,u2.name rname')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $genre  = array(1 => '维修单', 2 => '保养单');
            $status = array('-1' => '取消单', '0' => '待审', '1' => '审核通过', '2' => '审核不通过', '3' => '已接单', '4' => '已完成', '5' => '投诉', '6' => '投诉已处理');
            foreach ($list['data'] as $k => &$item) {
                $item['genre_text']  = $genre[$item['genre']];
                $item['status_text'] = $status[$item['status']];
                if (!empty($item['checktime'])) {
                    $item['checktime'] = date('Y-m-d H:i:s', $item['checktime']);
                }
                if (!empty($item['canceltime'])) {
                    $item['canceltime'] = date('Y-m-d H:i:s', $item['canceltime']);
                }
                if (!empty($item['finishtime'])) {
                    $item['finishtime'] = date('Y-m-d H:i:s', $item['finishtime']);
                }
                if (!empty($item['receive_time'])) {
                    $item['receive_time'] = date('Y-m-d H:i:s', $item['receive_time']);
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