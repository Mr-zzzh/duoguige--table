<?php

namespace app\mobile\model;

class Answer extends Common {

    public function GetAll($params) {
        $map = array();
        if (empty($params['qid'])) {
            show_json(0, '请传问题id');
        }
        $map['a.status'] = 1;
        $map['a.qid']    = intval($params['qid']);
        $list            = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->field('a.id,a.qid,a.answer,a.createtime,u.name,u.avatar')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        $data = array(
            'uid'        => $member['id'],
            'answer'     => trim($params['answer']),
            'qid'        => intval($params['qid']),
            'status'     => 1,
            'createtime' => time(),
        );
        if (empty($data['qid'])) {
            show_json(0, '问题id不能为空');
        }
        if (empty($data['answer'])) {
            show_json(0, '回答内容不能为空');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }
}