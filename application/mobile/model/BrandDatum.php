<?php

namespace app\mobile\model;

class BrandDatum extends Common {

    public function GetAll($params) {
        $map = array();
        if (empty($params['bid'])) {
            show_json(0, '请选择品牌');
        }
        if (!empty($params['keyword'])) {
            $map['name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['bid'] = intval($params['bid']);
        $list       = $this->field('id,name,datum,size,view,download')
            ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
        show_json(1, $list);
    }

    public function GetOne($id) {
        $this->where('id', $id)->setInc('view');
        $item = $this->where('id', $id)->field('datum')->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
        }
        show_json(1, $item);
    }

    public function Download($id) {
        global $member;
        $data['uid']  = $member['id'];
        $data['bdid'] = $id;
        if (db('download')->where($data)->value('id')) {
            show_json(0, '您已下载过该文件');
        }
        $this->where('id', $id)->setInc('download');
        $data['createtime'] = time();
        $item               = db('download')->insert($data);
        if ($item) {
            show_json(1, '成功');
        } else {
            show_json(0, '失败');
        }
    }

}