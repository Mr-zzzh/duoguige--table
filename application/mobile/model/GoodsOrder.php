<?php

namespace app\mobile\model;

class GoodsOrder extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $map['status'] = intval($params['status']);
        }
        if (!empty($params['keyword'])) {
            $map['ordersn|expresscom|expresssn'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        $list = $this->where($map)->paginate($params['limit'])->toArray();
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
            'uid' => intval($params['uid']),
            'gid' => intval($params['gid']),
            'ordersn' => trim($params['ordersn']),
            'number' => intval($params['number']),
            'money' => trim($params['money']) * 1,
            'status' => intval($params['status']),
            'paytype' => intval($params['paytype']),
            'tradeno' => intval($params['tradeno']),
            'addressid' => intval($params['addressid']),
            'freight' => trim($params['freight']) * 1,
            'expresscom' => trim($params['expresscom']),
            'expresssn' => trim($params['expresssn']),
            'paytime' => intval($params['paytime']),
            'finishtime' => intval($params['finishtime']),
            'canceltime' => intval($params['canceltime']),
            'delivertime' => intval($params['delivertime']),
            'createtime' => time(),
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
            'uid' => intval($params['uid']),
            'gid' => intval($params['gid']),
            'ordersn' => trim($params['ordersn']),
            'number' => intval($params['number']),
            'money' => trim($params['money']) * 1,
            'status' => intval($params['status']),
            'paytype' => intval($params['paytype']),
            'tradeno' => intval($params['tradeno']),
            'addressid' => intval($params['addressid']),
            'freight' => trim($params['freight']) * 1,
            'expresscom' => trim($params['expresscom']),
            'expresssn' => trim($params['expresssn']),
            'paytime' => intval($params['paytime']),
            'finishtime' => intval($params['finishtime']),
            'canceltime' => intval($params['canceltime']),
            'delivertime' => intval($params['delivertime']),
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
            $item = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}