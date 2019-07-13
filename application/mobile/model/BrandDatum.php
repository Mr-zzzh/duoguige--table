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
        global $member;
        $this->where('id', $id)->setInc('view');
        $item = $this->where('id', $id)->field('datum')->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
            if ($member) {
                if (db('download')->where(array('uid' => $member['id'], 'bdid' => $id))->value('id')) {
                    $item['is_collect'] = 1;
                } else {
                    $item['is_collect'] = 0;
                }
            } else {
                $item['is_collect'] = 0;
            }
        }
        show_json(1, $item);
    }

    public function Download($id) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data['uid']  = $member['id'];
        $data['bdid'] = $id;
        if (db('download')->where($data)->value('id')) {
            show_json(0, '您已收藏过该文件');
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