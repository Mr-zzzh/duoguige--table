<?php

namespace app\mobile\model;

class Question extends Common {

    public function GetAll($params) {
        $map           = array();
        $map['a.type'] = 1;
        if (!empty($params['keyword'])) {
            $map['a.title'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('a')
            ->join('answer b', 'a.id=b.qid', 'left')
            ->field('a.id,a.title,a.thumb,count(b.id) number')
            ->where($map)->group('a.id')->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        $data = array(
            'uid'        => $member['id'],
            'title'      => trim($params['title']),
            'type'       => 1,
            'createtime' => time(),
        );
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
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('answer b', 'a.id=b.qid', 'left')
            ->join('user u', 'a.uid=u.id', 'left')
            ->field('a.id,a.title,a.thumb,a.createtime,count(b.id) number,u.name,u.avatar')
            ->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
            if (!empty($item['thumb'])) {
                $item['thumb'] = explode(',', $item['thumb']);
            }
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

    public function MyQuestion($params) {
        global $member;
        $map           = array();
        $map['a.type'] = 1;
        $map['a.uid']  = $member['id'];
        $list          = $this->alias('a')
            ->join('answer b', 'a.id=b.qid', 'left')
            ->field('a.id,a.title,a.thumb,a.createtime,count(b.id) number')
            ->where($map)->group('a.id')->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                if (!empty($item['thumb'])) {
                    $item['thumb'] = explode(',', $item['thumb']);
                }
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }
}