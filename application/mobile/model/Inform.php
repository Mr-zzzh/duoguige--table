<?php

namespace app\mobile\model;

class Inform extends Common {

    //未读消息数量
    public function unreadnum() {
        global $member;
        $total = intval($this->where(array('uid' => $member['id'], 'read' => 0))->count('id'));
        return $total;
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
            $map['a.post|a.description|a.duty|a.name|a.phone|c.company_name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $map['a.status'] = 1;
        $list            = $this->alias('a')
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

    public function EditOne($params, $id) {
        global $member;
        $data = array(
            'uid'          => $member['id'],
            'company_name' => trim($params['company_name']),
            'phone'        => trim($params['phone']),
            'name'         => trim($params['name']),
            'area'         => trim($params['area']),
            'address'      => trim($params['address']),
            'number'       => intval($params['number']),
            'brand'        => trim($params['brand']),
            'image'        => trim($params['image']),
            'createtime'   => time(),
        );
        if (empty($data['company_name'])) {
            show_json(0, '公司名称不能为空');
        }
        if (empty($data['phone'])) {
            show_json(0, '联系电话不能为空');
        }
        if (empty($data['name'])) {
            show_json(0, '公司法人姓名不能为空');
        }
        if (empty($data['area'])) {
            show_json(0, '公司地址不能为空');
        }
        if (empty($data['address'])) {
            show_json(0, '详细地址不能为空');
        }
        if (empty($data['number'])) {
            show_json(0, '电梯数量不能为空');
        }
        if (empty($data['brand'])) {
            show_json(0, '电梯品牌不能为空');
        }
        if (empty($data['image'])) {
            show_json(0, '营业执照不能为空');
        }
        $user['status'] = 0;
        $user['type']   = 3;
        db('user')->where('id', $member['id'])->update($user);
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