<?php

namespace app\mobile\model;

class Fault extends Common {

    public function GetAll($params) {
        global $member;
        $map = array();
        if (!empty($params['keyword'])) {
            $map['f.fault_code|f.models|f.paraphrase|f.dispose|b.name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
            if (!empty($member)) {
                $history            = array();
                $history['uid']     = $member['id'];
                $history['type']    = 2;
                $history['content'] = trim($params['keyword']);
                if (!db('search_history')->where($history)->value('id')) {
                    $history['createtime'] = time();
                    db('search_history')->insert($history);
                }
            }
        }
        if (!empty($params['bid'])) {
            $map['f.bid'] = intval($params['bid']);
        }
        $list = $this->alias('f')
            ->join('brand b', 'f.bid=b.id', 'left')
            ->field('f.*,b.name as brand')
            ->where($map)->order('f.createtime desc')->paginate($params['limit'])->toArray();
        show_json(1, $list);
    }

    public function GetOne($id) {
        $item = $this->alias('f')
            ->join('brand b', 'f.bid=b.id', 'left')
            ->where('f.id', $id)->field('f.*,b.name as brand')->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}