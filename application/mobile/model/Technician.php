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
            ->field('u.id,t.name,u.avatar,u.phone')
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

    public function AddOne($params) {
        $data = array(
            'uid'              => intval($params['uid']),
            'name'             => trim($params['name']),
            'sex'              => intval($params['sex']),
            'idcardno'         => trim($params['idcardno']),
            'company_name'     => trim($params['company_name']),
            'license_number'   => trim($params['license_number']),
            'company_image'    => trim($params['company_image']),
            'prove_image'      => trim($params['prove_image']),
            'technician_image' => trim($params['technician_image']),
            'dimission'        => trim($params['dimission']),
            'createtime'       => time(),
        );
        if ($this->data($data, true)->isUpdate(false)->save()) {
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array(
            'uid'              => intval($params['uid']),
            'name'             => trim($params['name']),
            'sex'              => intval($params['sex']),
            'idcardno'         => trim($params['idcardno']),
            'company_name'     => trim($params['company_name']),
            'license_number'   => trim($params['license_number']),
            'company_image'    => trim($params['company_image']),
            'prove_image'      => trim($params['prove_image']),
            'technician_image' => trim($params['technician_image']),
            'dimission'        => trim($params['dimission']),
        );
        if ($this->save($data, array('id' => $id)) !== false) {
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = db('user')->alias('u')
            ->join('technician t', 't.uid=u.id', 'left')
            ->join('question q', 'q.master_id=u.id and q.type=2', 'left')
            ->field('u.id,t.name,u.avatar,u.phone,u.intro,count(q.id) number')
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
            }
            unset($item);
        }
        show_json(1, $list);
    }

}