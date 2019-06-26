<?php

namespace app\mobile\model;

class Goods extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['keyword'])) {
            $map['name|specification|model|manufacturers|phone|label|intro|area|province|color|address'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['cid'])) {
            $map['cid'] = intval($params['cid']);
        }
        $list = $this->field('id,name,thumbnail,price')->where($map)->order('sale_number desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function GetOne($id) {
        global $member;
        $item = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.*,b.name bname')->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
            if (!empty($item['image'])) {
                $item['image'] = explode(',', $item['image']);
            }
            if (!empty($item['label'])) {
                $item['label'] = explode(',', $item['label']);
                if (!empty($member)) {
                    foreach ($item['label'] as &$v) {
                        if (db('browse_history')->where(array('uid' => $member['id'], 'lable' => $v))->value('id')) {
                            db('browse_history')->where(array('uid' => $member['id'], 'lable' => $v))->setInc('number');
                        } else {
                            $data['uid']    = $member['id'];
                            $data['lable']  = $v;
                            $data['number'] = 1;
                            db('browse_history')->insert($data);
                        }
                    }
                }
            }
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}