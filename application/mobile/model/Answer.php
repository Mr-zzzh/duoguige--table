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
        $data = array(
            'uid'        => intval($params['uid']),
            'answer'     => trim($params['answer']),
            'qid'        => intval($params['qid']),
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        $this->checkData($data, 0);
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }
}