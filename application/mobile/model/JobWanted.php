<?php

namespace app\mobile\model;

class JobWanted extends Common {

    public function GetAll($params) {
        $map   = array();
        $where = '';
        if (!empty($params['type'])) {
            if (intval($params['type']) == 1) {
                $where = "-3 days";
            } elseif (intval($params['type']) == 2) {
                $where = "-7 days";
            } elseif (intval($params['type']) == 3) {
                $where = "-1 months";
            } else {
                $where = '';
            }
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
            ->where($map)
            ->whereTime('a.createtime', $where)->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        global $member;
        if ($member['type'] == 2 || $member['type'] == 3) {
            show_json(0, '此账号没有发布求职信息权限!');
        }
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'uid'        => $member['id'],
            'post'       => trim($params['post']),
            'salary'     => intval($params['salary']),
            'arrival'    => trim($params['arrival']),
            'province'   => intval($params['province']),
            'city'       => intval($params['city']),
            'area'       => intval($params['area']),
            'intro'      => trim($params['intro']),
            'education'  => trim($params['education']),
            'name'       => trim($params['name']),
            'address'    => trim($params['address']),
            'status'     => 0,
            'createtime' => time(),
        );
        if (empty($data['post'])) {
            show_json(0, '求职岗位不能为空');
        }
        if (empty($data['arrival'])) {
            show_json(0, '到岗时间不能为空');
        }
        if (empty($data['education'])) {
            show_json(0, '学历要求不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '姓名不能为空');
        }
        if (empty($data['intro'])) {
            show_json(0, '自我描述不能为空');
        }
        if (empty($data['salary'])) {
            show_json(0, '薪资范围不能为空');
        }
        if (empty($data['province'])) {
            show_json(0, '省不能为空');
        }
        if (empty($data['city'])) {
            show_json(0, '市不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '区不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
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
            'post'       => trim($params['post']),
            'salary'     => intval($params['salary']),
            'arrival'    => trim($params['arrival']),
            'province'   => intval($params['province']),
            'city'       => intval($params['city']),
            'area'       => intval($params['area']),
            'intro'      => trim($params['intro']),
            'education'  => trim($params['education']),
            'name'       => trim($params['name']),
            'address'    => trim($params['address']),
            'status'     => 0,
            'createtime' => time(),
        );
        if (empty($data['post'])) {
            show_json(0, '求职岗位不能为空');
        }
        if (empty($data['arrival'])) {
            show_json(0, '到岗时间不能为空');
        }
        if (empty($data['education'])) {
            show_json(0, '学历要求不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '姓名不能为空');
        }
        if (empty($data['intro'])) {
            show_json(0, '自我描述不能为空');
        }
        if (empty($data['salary'])) {
            show_json(0, '薪资范围不能为空');
        }
        if (empty($data['province'])) {
            show_json(0, '省不能为空');
        }
        if (empty($data['city'])) {
            show_json(0, '市不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '区不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->field('a.id,a.post,a.arrival,a.province,a.city,a.area,a.intro,a.education,a.name,a.address,a.salary,a.createtime,s.name salary_text,u.phone,u.avatar')->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item                  = $item->toArray();
            $item['province_text'] = city_name($item['province']);
            $item['city_text']     = city_name($item['city']);
            $item['area_text']     = city_name($item['area']);
            $item['createtime']    = date('Y-m-d', $item['createtime']);
        }
        show_json(1, $item);
    }

}