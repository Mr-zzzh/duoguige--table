<?php

namespace app\admin\model;

class Remind extends Common {
    //未读消息数量
    public function unreadnum() {
        $total = intval($this->where(array('status' => 0))->count('id'));
        return $total;
    }

    public function GetAll($params) {
        $list = $this->alias('a')
            ->join('goods_order b', 'a.oid=b.id', 'left')
            ->join('goods c', 'b.gid=c.id', 'left')
            ->field('a.id,a.createtime,c.name')
            ->order('a.createtime desc')->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function GetOne($id) {
        $this->save(array('status' => 1), array('id' => $id));
        $item = $this->alias('a')
            ->join('goods_order b', 'a.oid=b.id', 'left')
            ->join('goods c', 'b.gid=c.id', 'left')
            ->join('user u', 'b.uid=u.id', 'left')
            ->field('a.id,a.createtime,c.name,b.ordersn,u.name uname')
            ->where('a.id', $id)
            ->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}