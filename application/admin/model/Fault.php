<?php

namespace app\admin\model;

class Fault extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['a.fault_code|a.models|a.paraphrase|a.dispose'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['bid'])) {
            $map['a.bid'] = intval($params['bid']);
        }
        $list = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.*,b.name bname')->where($map)
            ->order('a.bid asc,a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            'fault_code' => trim($params['fault_code']),
            'bid'        => intval($params['bid']),
            'models'     => trim($params['models']),
            'paraphrase' => trim($params['paraphrase']),
            'dispose'    => trim($params['dispose']),
            'createtime' => time(),
        );
        if (empty($data['fault_code'])) {
            show_json(0, '请传故障代码');
        }
        if (empty($data['bid'])) {
            show_json(0, '请传品牌id');
        }
        if (empty($data['models'])) {
            show_json(0, '请传适用机型');
        }
        if (empty($data['paraphrase'])) {
            show_json(0, '请传代码释义');
        }
        if (empty($data['dispose'])) {
            show_json(0, '请传处理办法');
        }
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
            'fault_code' => trim($params['fault_code']),
            'bid'        => intval($params['bid']),
            'models'     => trim($params['models']),
            'paraphrase' => trim($params['paraphrase']),
            'dispose'    => trim($params['dispose']),
        );
        if (empty($data['fault_code'])) {
            show_json(0, '请传故障代码');
        }
        if (empty($data['bid'])) {
            show_json(0, '请传品牌id');
        }
        if (empty($data['models'])) {
            show_json(0, '请传适用机型');
        }
        if (empty($data['paraphrase'])) {
            show_json(0, '请传代码释义');
        }
        if (empty($data['dispose'])) {
            show_json(0, '请传处理办法');
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.*,b.name bname')->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}