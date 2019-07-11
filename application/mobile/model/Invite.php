<?php

namespace app\mobile\model;

class Invite extends Common {

    public function Salary() {
        $list = db('salary')->select();
        show_json(1, array('data' => $list));
    }

    public function Experience() {
        $list = db('experience')->select();
        show_json(1, array('data' => $list));
    }

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
            $map['a.post|a.description|a.duty|a.name|a.phone|c.company_name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['a.status'] = 1;
        $list            = $this->alias('a')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->join('company c', 'a.uid=c.uid', 'left')
            ->field('a.id,a.post,a.province,a.city,a.createtime,a.name,a.phone,s.name salary_text,c.company_name')
            ->where($map)
            ->whereTime('a.createtime', $where)->order('a.createtime desc')->paginate($params['limit'])->toArray();
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
        global $member;
        if ($member['type'] != 2 || $member['type'] != 3) {
            show_json(0, '此账号没有发布招聘信息权限!');
        }
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        $data = array(
            'uid'         => $member['id'],
            'post'        => trim($params['post']),
            'education'   => trim($params['education']),
            'salary'      => trim($params['salary']),
            'experience'  => trim($params['experience']),
            'province'    => intval($params['province']),
            'city'        => intval($params['city']),
            'area'        => intval($params['area']),
            'address'     => trim($params['address']),
            'description' => trim($params['description']),
            'duty'        => trim($params['duty']),
            'name'        => trim($params['name']),
            'phone'       => trim($params['phone']),
            'number'      => trim($params['number']),
            'status'      => 0,
            'createtime'  => time(),
        );
        if (empty($data['post'])) {
            show_json(0, '招聘岗位不能为空');
        }
        if (empty($data['education'])) {
            show_json(0, '学历要求不能为空');
        }
        if (empty($data['salary'])) {
            show_json(0, '薪资范围不能为空');
        }
        if (empty($data['experience'])) {
            show_json(0, '经验要求不能为空');
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
        if (empty($data['description'])) {
            show_json(0, '岗位描述不能为空');
        }
        if (empty($data['duty'])) {
            show_json(0, '岗位职责不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '联系人姓名不能为空');
        }
        if (empty($data['phone'])) {
            show_json(0, '联系电话不能为空');
        }
        if (empty($data['number'])) {
            show_json(0, '招聘人数不能为空');
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
            'post'        => trim($params['post']),
            'education'   => trim($params['education']),
            'salary'      => trim($params['salary']),
            'experience'  => trim($params['experience']),
            'province'    => intval($params['province']),
            'city'        => intval($params['city']),
            'area'        => intval($params['area']),
            'address'     => trim($params['address']),
            'description' => trim($params['description']),
            'duty'        => trim($params['duty']),
            'name'        => trim($params['name']),
            'phone'       => trim($params['phone']),
            'number'      => trim($params['number']),
            'status'      => 0,
        );
        if (empty($data['post'])) {
            show_json(0, '招聘岗位不能为空');
        }
        if (empty($data['education'])) {
            show_json(0, '学历要求不能为空');
        }
        if (empty($data['salary'])) {
            show_json(0, '薪资范围不能为空');
        }
        if (empty($data['experience'])) {
            show_json(0, '经验要求不能为空');
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
        if (empty($data['description'])) {
            show_json(0, '岗位描述不能为空');
        }
        if (empty($data['duty'])) {
            show_json(0, '岗位职责不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '联系人姓名不能为空');
        }
        if (empty($data['phone'])) {
            show_json(0, '联系电话不能为空');
        }
        if (empty($data['number'])) {
            show_json(0, '招聘人数不能为空');
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->join('experience e', 'a.experience=e.id', 'left')
            ->field('a.id,a.post,a.education,a.province,a.city,a.area,description,a.duty,a.name,a.phone,a.address,a.number,a.createtime,s.name salary_text,e.name experience_text')
            ->where('a.id', $id)->find();
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