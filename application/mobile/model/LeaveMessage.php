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
        $data = array(
            'uid'         => intval($params['uid']),
            'nid'         => intval($params['nid']),
            'type'        => intval($params['type']),
            'content'     => trim($params['content']),
            'like_number' => intval($params['like_number']),
            'createtime'  => time(),
        );
        $this->checkData($data, 0);
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    private function checkData(&$data, $id = 0) {
        //TODO 数据校验
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array(
            'uid'         => intval($params['uid']),
            'nid'         => intval($params['nid']),
            'type'        => intval($params['type']),
            'content'     => trim($params['content']),
            'like_number' => intval($params['like_number']),
        );
        $this->checkData($data, $id);
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}