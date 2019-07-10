<?php

namespace app\admin\model;

class Share extends Common {

    public function AddOne($params) {
        $data = array(
            'title'      => trim($params['title']),
            'icon'       => trim($params['icon']),
            'intro'      => trim($params['intro']),
            'share_link' => trim($params['share_link']),
            'createtime' => time(),
        );
        if (empty($data['title'])) {
            show_json(0, '请传分享标题');
        }
        if (empty($data['icon'])) {
            show_json(0, '请传分享图标');
        }
        if (empty($data['intro'])) {
            show_json(0, '请传分享描述');
        }
        if (empty($data['share_link'])) {
            show_json(0, '请传分享链接');
        }
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array(
            'title'      => trim($params['title']),
            'icon'       => trim($params['icon']),
            'intro'      => trim($params['intro']),
            'share_link' => trim($params['share_link']),
            'createtime' => time(),
        );
        if (empty($data['title'])) {
            show_json(0, '请传分享标题');
        }
        if (empty($data['icon'])) {
            show_json(0, '请传分享图标');
        }
        if (empty($data['intro'])) {
            show_json(0, '请传分享描述');
        }
        if (empty($data['share_link'])) {
            show_json(0, '请传分享链接');
        }
        if ($this->select()) {
            if ($this->data($data, true)->isUpdate(false)->save()) {
                //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
                show_json(1, '添加成功');
            } else {
                show_json(0, '添加失败');
            }
        } else {
            if ($this->save($data, array('id' => $id)) !== false) {
                //logs('编辑??,ID:' . $id, 3);
                show_json(1, '编辑成功');
            } else {
                show_json(0, '编辑失败');
            }
        }
    }

    public function GetOne() {
        $item = $this->order('createtime desc')->limit(1)->find();
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
        }
        show_json(1, $item);
    }

}