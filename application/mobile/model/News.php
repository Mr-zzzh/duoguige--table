<?php

namespace app\mobile\model;

class News extends Common {

    public function GetAll($params) {
        $map    = array();
        $banner = db('banner')->where(array('type' => 3, 'status' => 1))
            ->field('id,url,jumpurl,newsid')->order('sort asc,createtime desc')->select();
        $id     = db('banner')->where(array('type' => 3, 'status' => 1))->column('newsid');
        if (!empty($params['keyword'])) {
            $map['title|content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['status']  = 1;
        $map['id']      = array('not in', $id);
        $list           = $this->where($map)
            ->field('id,title,thumb,type,view_number,like_number')
            ->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
        $list['banner'] = $banner;
        show_json(1, $list);
    }

    public function GetOne($id) {
        $this->where('id', $id)->setInc('view_number');
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item                   = $item->toArray();
            $item['comment_number'] = db('leave_message')->where(array('nid' => $id, 'type' => 1))->count('id');
            $item['createtime']     = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}