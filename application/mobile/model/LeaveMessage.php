<?php

namespace app\mobile\model;

class LeaveMessage extends Common {

    public function GetAll($params) {
        global $member;
        $map = array();
        if (empty($params['nid'])) {
            show_json(0, '请传新闻id');
        }
        $map['a.type'] = 1;
        $map['a.nid']  = intval($params['nid']);
        $list          = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->field('a.id,a.content,a.like_number,a.createtime,u.name,u.avatar')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (db('like')->where(array('uid' => $member['id'], 'nid' => $item['id'], 'type' => 2))->value('id')) {
                    $item['is_like'] = 1;
                } else {
                    $item['is_like'] = 2;
                }
                $item['day']  = date('Y-m-d', $item['createtime']);
                $item['time'] = date('H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        $data = array(
            'uid'         => $member['id'],
            'nid'         => intval($params['nid']),
            'type'        => 1,
            'content'     => trim($params['content']),
            'like_number' => 0,
            'createtime'  => time(),
        );
        if (empty($data['nid'])) {
            show_json(0, '请传新闻id');
        }
        if (empty($data['content'])) {
            show_json(0, '请传评论内容');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '发布成功');
        } else {
            show_json(0, '发布失败');
        }
    }
}