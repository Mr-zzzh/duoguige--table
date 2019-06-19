<?php

namespace app\mobile\model;

class Invite extends Common {

    public function Salary() {
        $list = db('salary')->select();
        show_json(1, $list);
    }

    public function Experience() {
        $list = db('experience')->select();
        show_json(1, $list);
    }

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
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
            $map['a.post|a.description|a.duty|a.name|a.phone'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->alias('a')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->join('company c', 'a.uid=c.uid', 'left')
            ->field('a.id,a.post,a.province,a.city,a.createtime,a.name,a.phone,s.name salary_text,c.company_name')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['province_text'] = city_name($item['province']);
                $item['city_text']     = city_name($item['city']);
                $item['createtime']    = date('Y-m-d', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'uid'         => intval($params['uid']),
            'post'        => trim($params['post']),
            'salary'      => trim($params['salary']),
            'experience'  => trim($params['experience']),
            'province'    => intval($params['province']),
            'city'        => intval($params['city']),
            'description' => trim($params['description']),
            'duty'        => trim($params['duty']),
            'name'        => trim($params['name']),
            'phone'       => trim($params['phone']),
            'status'      => intval($params['status']),
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
            'post'        => trim($params['post']),
            'salary'      => trim($params['salary']),
            'experience'  => trim($params['experience']),
            'province'    => intval($params['province']),
            'city'        => intval($params['city']),
            'description' => trim($params['description']),
            'duty'        => trim($params['duty']),
            'name'        => trim($params['name']),
            'phone'       => trim($params['phone']),
            'status'      => intval($params['status']),
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