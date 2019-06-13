<?php

namespace app\admin\model;

class JobWanted extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['a.status'] = intval($params['status']);
        }
        if (!empty($params['keyword'])) {
            $map['a.post|a.intro'] = array('LIKE', '%' . trim($params['keyword']) . '%');
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
        $list = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->field('a.*,u.name uname,s.name sname')
            ->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            $status = array(0 => '待审', 1 => '通过', 2 => '不通过', 3 => '招聘结束');
            foreach ($list['data'] as $k => &$item) {
                $item['status_text']   = $status[$item['status']];
                $item['province_text'] = city_name($item['province']);
                $item['city_text']     = city_name($item['city']);
                $item['area_text']     = city_name($item['area']);
                $item['createtime']    = date('Y-m-d H:i:s', $item['createtime']);
                if (!empty($item['checktime'])) {
                    $item['checktime'] = date('Y-m-d H:i:s', $item['checktime']);
                }
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
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
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
            'uid'      => intval($params['uid']),
            'post'     => trim($params['post']),
            'salary'   => trim($params['salary']),
            'arrival'  => trim($params['arrival']),
            'province' => intval($params['province']),
            'city'     => intval($params['city']),
            'intro'    => trim($params['intro']),
            'status'   => intval($params['status']),
        );
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