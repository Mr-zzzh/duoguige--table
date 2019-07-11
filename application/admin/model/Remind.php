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
            ->join('user u', 'b.uid=u.id', 'left')
            ->field('a.id,a.status,a.createtime,c.name,u.name uname,u.phone uphone,b.ordersn')
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
            ->field('a.id,a.createtime,c.name,b.ordersn,u.name uname,u.phone uphone')
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

    //审核通知添加
    public function InformAdd($uid, $status, $type, $checkid, $content) {
        $a     = array('1' => '公司信息', '2' => '技师信息', '3' => '维保信息', '4' => '招聘信息', '5' => '求职');
        $b     = array('1' => '通过', '2' => '未通过');
        $title = '您提交的' . $a[$type] . '审核' . $b[$status] . '审核';
        $data  = [
            'uid'        => $uid,
            'title'      => $title,
            'status'     => $status,
            'content'    => $content,
            'type'       => $type,
            'checkid'    => $checkid,
            'read'       => 0,
            'createtime' => time(),
        ];
        db('inform')->insert($data);
    }

}