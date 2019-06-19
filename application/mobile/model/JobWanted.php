<?php

namespace app\mobile\model;

class JobWanted extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['salary'])) {
            $map['a.salary'] = intval($params['salary']);
        }
        if (!empty($params['province'])) {
            $map['a.province'] = intval($params['province']);
        }
        if (!empty($params['city'])) {
            $map['a.city'] = intval($params['city']);
        }
        if (!empty($params['area'])) {
            $map['a.area'] = intval($params['area']);
        }
        if (!empty($params['keyword'])) {
            $map['a.post|a.intro|a.address|a.name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['a.status'] = 1;
        $list            = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->field('a.id,a.post,a.name,a.createtime,a.intro,s.name salary_text,u.phone,u.avatar')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'uid'        => intval($params['uid']),
            'post'       => trim($params['post']),
            'salary'     => trim($params['salary']),
            'arrival'    => trim($params['arrival']),
            'province'   => intval($params['province']),
            'city'       => intval($params['city']),
            'intro'      => trim($params['intro']),
            'status'     => intval($params['status']),
            'createtime' => time(),
        );
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array(
            'uid'      => intval($params['uid']),
            'post'     => trim($params['post']),
            'salary'   => trim($params['salary']),
            'arrival'  => trim($params['arrival']),
            'province' => intval($params['province']),
            'city'     => intval($params['city']),
            'intro'    => trim($params['intro']),
            'status'   => intval($params['status']),
        );
        $this->checkData($data, $id);
        if ($this->save($data, array('id' => $id)) !== false) {
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