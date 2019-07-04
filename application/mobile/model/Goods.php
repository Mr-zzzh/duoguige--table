<?php

namespace app\mobile\model;

class Goods extends Common {

    public function GetAll($params) {
        global $member;
        $map = array();
        if (!empty($params['keyword'])) {
            $map['name|specification|model|manufacturers|phone|label|intro|area|province|color|address'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['cid'])) {
            $map['cid'] = intval($params['cid']);
        }
        $where = '';
        if (empty($params['cid']) && empty($params['keyword'])) {
            if (!empty($member)) {
                $label = db('browse_history')->where(array('uid' => $member['id']))
                    ->order('number desc')->limit(3)->column('lable');
                foreach ($label as $k1 => &$v1) {
                    if ($k1 == 2) {
                        $where .= "FIND_IN_SET('" . $v1 . "',label)";
                    } else {
                        $where .= "FIND_IN_SET('" . $v1 . "',label) or ";
                    }
                }
                unset($v1);
            }
        }
        $list = $this->field('id,name,thumbnail,price')->where($map)->where($where)
            ->order('sort asc,sale_number desc')->paginate($params['limit'])->toArray();
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
        $this->where('id', $id)->setInc('view_number');
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