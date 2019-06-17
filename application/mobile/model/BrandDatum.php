<?php

namespace app\mobile\model;

class BrandDatum extends Common {

    public function GetAll($params) {
        $map = array();
        if (empty($params['bid'])) {
            show_json(0, '请选择品牌');
        }
        $map['bid'] = intval($params['bid']);
        $list       = $this->field('id,name,datum,size,view,download')
            ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
        show_json(1, $list);
    }

    public function GetOne($id) {
        $item = $this->where('id', $id)->value('datum');
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
        }
        show_json(1, $item);
    }

}