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
        $item = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.*,b.name bname')->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['image']      = explode(',', $item['image']);
            $item['label']      = explode(',', $item['label']);
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}