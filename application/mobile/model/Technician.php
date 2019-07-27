<?php

namespace app\mobile\model;

class Technician extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['keyword'])) {
            $map['t.name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['u.status'] = 1;
        $map['u.type']   = 2;
        $list            = db('user')->alias('u')
            ->join('technician t', 't.uid=u.id', 'left')
            ->field('u.id,u.name,u.avatar,u.phone')
            ->where($map)->order('u.createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['label'] = '已认证维修大师';
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function GetOne($id) {
        $item = db('user')->alias('u')
            ->join('technician t', 't.uid=u.id', 'left')
            ->join('question q', 'q.master_id=u.id and q.type=2', 'left')
            ->join('answer a', 'a.qid=q.id and a.status=1', 'left')
            ->field('u.id,t.name,u.avatar,u.phone,u.intro,count(a.id) number')
            ->where('u.id', $id)
            ->find();;
        if (empty($item)) {
            show_json(1);
        } else {
            $item['label'] = '已认证维修大师';
        }
        show_json(1, $item);
    }

    public function Question($params) {
        $map = array();
        if (empty($params['id'])) {
            show_json(0, '请传大师id');
        }
        $map['q.master_id'] = intval($params['id']);
        $map['q.type']      = 2;
        $list               = db('question')->alias('q')
            ->join('answer a', 'q.id=a.qid', 'left')
            ->field('q.id,q.title,q.thumb,a.answer')
            ->where($map)->group('q.id')->order('q.createtime desc')
            ->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
                if (empty($item['answer'])) {
                    $item['answer'] = '';
                }
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function QuestionAdd($params) {
        global $member;
        $data = array(
            'uid'        => $member['id'],
            'master_id'  => intval($params['master_id']),
            'title'      => trim($params['title']),
            'type'       => 2,
            'createtime' => time(),
        );
        if (empty($data['master_id'])) {
            show_json(0, '大师ID不能为空');
        }
        if (empty($data['title'])) {
            show_json(0, '问题不能为空');
        }
        if (!empty($params['thumb'])) {
            if (is_array($params['thumb'])) {
                $data['thumb'] = implode(',', trim($params['thumb']));
            } else {
                $data['thumb'] = trim($params['thumb']);
            }
        }
        if (db('question')->insert($data)) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

}