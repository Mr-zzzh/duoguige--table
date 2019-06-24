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
            ->where($map)->order('m.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $genre  = array(1 => '维修单', 2 => '保养单');
            $status = array('-1' => '取消', '0' => '待审', '1' => '审核通过', '2' => '审核不通过', '3' => '已接单', '4' => '已完成', '5' => '投诉', '6' => '投诉已处理');
            foreach ($list['data'] as $k => &$item) {
                $item['genre_text']  = $genre[$item['genre']];
                $item['status_text'] = $status[$item['status']];
                if (!empty($item['checktime'])) {
                    $item['checktime'] = date('Y-m-d H:i:s', $item['checktime']);
                }
                if (!empty($item['canceltime'])) {
                    $item['canceltime'] = date('Y-m-d H:i:s', $item['canceltime']);
                }
                $item['province_text'] = city_name($item['province']);
                $item['city_text']     = city_name($item['city']);
                $item['area_text']     = city_name($item['area']);
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
            db('plan')->where(array('mid' => $id))->delete();
            db('evaluate')->where(array('mid' => $id))->delete();
            db('complaint')->where(array('mid' => $id))->delete();
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('m')
            ->join('user u1', 'm.uid=u1.id', 'left')
            ->join('user u2', 'm.receive_id=u2.id', 'left')
            ->field('m.*,u1.name uname,u2.name rname')
            ->where('m.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $genre                 = array(1 => '维修单', 2 => '保养单');
            $status                = array('-1' => '取消', '0' => '待审', '1' => '审核通过', '2' => '审核不通过', '3' => '已接单', '4' => '已完成', '5' => '投诉', '6' => '投诉已处理');
            $item                  = $item->toArray();
            $item['genre_text']    = $genre[$item['genre']];
            $item['status_text']   = $status[$item['status']];
            $item['province_text'] = city_name($item['province']);
            $item['city_text']     = city_name($item['city']);
            $item['area_text']     = city_name($item['area']);
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
        show_json(1, $item);
    }

    public function EditStatus($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json('id数据错误');
        }
        if (empty($params['status'])) {
            show_json('请传审核状态');
        }
        $data['status']    = intval($params['status']);
        $data['checktime'] = time();
        if ($this->save($data, array('id' => intval($params['id']))) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '审核成功');
        } else {
            show_json(0, '审核失败');
        }
    }

}