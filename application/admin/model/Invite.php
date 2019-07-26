<?php

namespace app\admin\model;

class Invite extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['a.status'] = intval($params['status']);
        }
        if (!empty($params['keyword'])) {
            $map['a.post|a.description|a.duty|a.name|a.phone'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['salary'])) {
            $map['a.salary'] = intval($params['salary']);
        }
        if (!empty($params['experience'])) {
            $map['a.experience'] = intval($params['experience']);
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
            ->join('company u', 'a.uid=u.uid', 'left')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->join('experience e', 'a.experience=e.id', 'left')
            ->field('a.*,u.company_name uname,s.name sname,e.name ename')
            ->where($map)->order('a.createtime desc')->paginate($params['limit'])->toArray();
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
                if (empty($item['uname'])) {
                    $item['uname'] = db('technician')->where('uid', $item['uid'])->value('company_name');
                }
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
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function EditStatus($params) {
        if (empty($params['id']) || $params['id'] < 1) {
            show_json(0, 'id传输错误');
        }
        if (empty($params['status'])) {
            show_json(0, '请传审核状态');
        }
        $status = $this->where('id', intval($params['id']))->value('status');
        if ($status != 0) {
            show_json(1, '审核成功');
        }
        $data['status']    = intval($params['status']);
        $data['remark']    = trim($params['remark']);
        $data['checktime'] = time();
        if ($this->save($data, array('id' => intval($params['id']))) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            $uid = $this->where('id', intval($params['id']))->value('uid');
            inform_add($uid, $data['status'], 4, intval($params['id']), $data['remark']);
            show_json(1, '审核成功');
        } else {
            show_json(0, '审核失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('user u', 'a.uid=u.id', 'left')
            ->join('salary s', 'a.salary=s.id', 'left')
            ->join('experience e', 'a.experience=e.id', 'left')
            ->field('a.*,u.name uname,s.name sname,e.name ename')
            ->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $status                = array(0 => '待审', 1 => '通过', 2 => '不通过', 3 => '招聘结束');
            $item                  = $item->toArray();
            $item['status_text']   = $status[$item['status']];
            $item['province_text'] = city_name($item['province']);
            $item['city_text']     = city_name($item['city']);
            $item['area_text']     = city_name($item['area']);
            $item['createtime']    = date('Y-m-d H:i:s', $item['createtime']);
            if (!empty($item['checktime'])) {
                $item['checktime'] = date('Y-m-d H:i:s', $item['checktime']);
            }
        }
        show_json(1, $item);
    }

}