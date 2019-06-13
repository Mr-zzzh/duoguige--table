<?php

namespace app\admin\model;

use think\Cache;

class BrandDatum extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['a.name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['bid'])) {
            $map['a.bid'] = intval($params['bid']);
        }
        $list = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.*,b.name bname')->where($map)
            ->order('a.createtime desc')->paginate($params['limit'])->toArray();
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
            'bid'        => intval($params['bid']),
            'name'       => trim($params['name']),
            'datum'      => trim($params['datum']),
            'view'       => 0,
            'download'   => 0,
            'createtime' => time(),
        );
        if (empty($data['bid'])) {
            show_json(0, '请传品牌id');
        }
        if (empty($data['name'])) {
            show_json(0, '请传资料标题');
        }
        if (empty($data['datum'])) {
            show_json(0, '请传资料链接');
        }
        $size = Cache::get($data['datum']);
        if ($size / 1024 < 1024) {
            $data['size'] = ceil($size / 1024) . 'kb';
        } else {
            $data['size'] = ceil($size / 1024 / 1024) . 'M';
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
            'bid'   => intval($params['bid']),
            'name'  => trim($params['name']),
            'datum' => trim($params['datum']),
        );
        if (empty($data['bid'])) {
            show_json(0, '请传品牌id');
        }
        if (empty($data['name'])) {
            show_json(0, '请传资料标题');
        }
        if (empty($data['datum'])) {
            show_json(0, '请传资料链接');
        }
        if (!empty($params['view'])) {
            $data['view'] = intval($params['view']);
        }
        if (!empty($params['download'])) {
            $data['download'] = intval($params['download']);
        }
        $size = Cache::get($data['datum']);
        if (!empty($size)) {
            if ($size / 1024 < 1024) {
                $data['size'] = ceil($size / 1024) . 'kb';
            } else {
                $data['size'] = ceil($size / 1024 / 1024) . 'M';
            }
        }
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