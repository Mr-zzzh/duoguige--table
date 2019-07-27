<?php

namespace app\admin\model;

class Goods extends Common {

    public function GetAll($params) {
        $map = array();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $map['a.createtime'] = array('between', strtotime($params['starttime']) . ',' . strtotime($params['endtime']));
        }
        if (!empty($params['keyword'])) {
            $map['a.name|a.intro|a.phone|a.label|a.intro|a.area|a.address'] = array('LIKE', '%' . trim($params['keyword']) . '%');
        }
        if (!empty($params['bid'])) {
            $map['a.bid'] = intval($params['bid']);
        }
        if (!empty($params['cid'])) {
            $map['a.cid'] = intval($params['cid']);
        }
        $list = $this->alias('a')
            ->join('brand b', 'a.bid=b.id', 'left')
            ->field('a.id,a.thumbnail,a.name,a.subhead,a.sort,a.bid,a.manufacturers,a.phone,a.price,a.label,a.sale_number,a.createtime,b.name bnam')
            ->where($map)->order('a.sort asc,a.createtime desc')->paginate($params['limit'])->toArray();
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
            'name'          => trim($params['name']),
            'subhead'       => trim($params['subhead']),
            'sort'          => intval($params['sort']),
            'bid'           => intval($params['bid']),
            'cid'           => intval($params['cid']),
            'thumbnail'     => trim($params['thumbnail']),
            'specification' => trim($params['specification']),
            'model'         => trim($params['model']),
            'manufacturers' => trim($params['manufacturers']),
            'phone'         => trim($params['phone']),
            'price'         => trim($params['price']),
            'intro'         => trim($params['intro']),
            'address'       => trim($params['address']),
            'province'      => trim($params['province']),
            'area'          => trim($params['area']),
            'color'         => trim($params['color']),
            'sale_number'   => 0,
            'createtime'    => time(),
        );
        if (empty($data['name'])) {
            show_json('0', '请传商品名');
        }
        if (empty($data['subhead'])) {
            show_json('0', '请传副标题');
        }
        if (empty($data['sort'])) {
            show_json('0', '请传序号');
        }
        if (empty($data['bid'])) {
            show_json('0', '请传品牌id');
        }
        if (empty($data['cid'])) {
            show_json('0', '请传商品分类id');
        }
        if (empty($data['thumbnail'])) {
            show_json('0', '请传商品缩略图');
        }
        if (empty($data['specification'])) {
            show_json('0', '请传规格');
        }
        if (empty($data['model'])) {
            show_json('0', '请传型号');
        }
        if (empty($data['manufacturers'])) {
            show_json('0', '请传厂家名称');
        }
        if (empty($data['phone'])) {
            show_json('0', '请传销售电话');
        }
        if (empty($data['price'])) {
            show_json('0', '请传价格');
        }
        if (empty($data['intro'])) {
            show_json('0', '请传详情');
        }
        if (empty($data['area'])) {
            show_json('0', '请传产地');
        }
        if (empty($data['province'])) {
            show_json('0', '请传省份');
        }
        if (empty($data['address'])) {
            show_json('0', '请传地址');
        }
        if (empty($data['color'])) {
            show_json('0', '请传颜色');
        }
        if (empty($params['image'])) {
            show_json('0', '请传详情轮播图');
        }
        if (is_array($params['image'])) {
            $data['image'] = implode(',', $params['image']);
        } else {
            $data['image'] = $params['image'];
        }
        if (empty($params['label'])) {
            show_json('0', '请传标签');
        }
        if (is_array($params['label'])) {
            $data['label'] = implode(',', $params['label']);
        } else {
            $data['label'] = $params['label'];
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
            'name'          => trim($params['name']),
            'subhead'       => trim($params['subhead']),
            'sort'          => intval($params['sort']),
            'bid'           => intval($params['bid']),
            'cid'           => intval($params['cid']),
            'thumbnail'     => trim($params['thumbnail']),
            'specification' => trim($params['specification']),
            'model'         => trim($params['model']),
            'manufacturers' => trim($params['manufacturers']),
            'phone'         => trim($params['phone']),
            'price'         => trim($params['price']),
            'intro'         => trim($params['intro']),
            'address'       => trim($params['address']),
            'province'      => trim($params['province']),
            'area'          => trim($params['area']),
            'color'         => trim($params['color']),
        );
        if (empty($data['name'])) {
            show_json('0', '请传商品名');
        }
        if (empty($data['subhead'])) {
            show_json('0', '请传副标题');
        }
        if (empty($data['sort'])) {
            show_json('0', '请传序号');
        }
        if (empty($data['bid'])) {
            show_json('0', '请传品牌id');
        }
        if (empty($data['cid'])) {
            show_json('0', '请传商品分类id');
        }
        if (empty($data['thumbnail'])) {
            show_json('0', '请传商品缩略图');
        }
        if (empty($data['specification'])) {
            show_json('0', '请传规格');
        }
        if (empty($data['model'])) {
            show_json('0', '请传型号');
        }
        if (empty($data['manufacturers'])) {
            show_json('0', '请传厂家名称');
        }
        if (empty($data['phone'])) {
            show_json('0', '请传销售电话');
        }
        if (empty($data['price'])) {
            show_json('0', '请传价格');
        }
        if (empty($data['intro'])) {
            show_json('0', '请传详情');
        }
        if (empty($data['area'])) {
            show_json('0', '请传产地');
        }
        if (empty($data['province'])) {
            show_json('0', '请传省份');
        }
        if (empty($data['address'])) {
            show_json('0', '请传地址');
        }
        if (empty($data['color'])) {
            show_json('0', '请传颜色');
        }
        if (empty($params['image'])) {
            show_json('0', '请传详情轮播图');
        }
        if (is_array($params['image'])) {
            $data['image'] = implode(',', $params['image']);
        } else {
            $data['image'] = $params['image'];
        }
        if (empty($params['label'])) {
            show_json('0', '请传标签');
        }
        if (is_array($params['label'])) {
            $data['label'] = implode(',', $params['label']);
        } else {
            $data['label'] = $params['label'];
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
            ->join('goods_cate c', 'a.cid=c.id', 'left')
            ->field('a.*,b.name bname,c.name cname')->where('a.id', $id)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item               = $item->toArray();
            $item['image']      = explode(',', $item['image']);
            $item['label']      = explode(',', $item['label']);
            $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
        }
        show_json(1, $item);
    }

}