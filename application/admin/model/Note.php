<?php

namespace app\admin\model;

class Note extends Common {

    public function AddOne($params) {
        $data = array(
            'appkey'     => trim($params['appkey']),
            'tid'        => trim($params['tid']),
            'code'       => trim($params['code']),
            'service'    => trim($params['service']),
            'agreement'  => trim($params['agreement']),
            'createtime' => time(),
        );
        if (empty($data['appkey'])) {
            show_json(0, '请传短信appkey');
        }
        if (empty($data['tid'])) {
            show_json(0, '请传模板id');
        }
        if (empty($data['code'])) {
            show_json(0, '请传短信验证码变量');
        }
        if (empty($data['service'])) {
            show_json(0, '请传客服电话');
        }
        if (empty($data['agreement'])) {
            show_json(0, '请传协议');
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
            'appkey'     => trim($params['appkey']),
            'tid'        => trim($params['tid']),
            'code'       => trim($params['code']),
            'service'    => trim($params['service']),
            'agreement'  => trim($params['agreement']),
            'createtime' => time(),
        );
        if (empty($data['appkey'])) {
            show_json(0, '请传短信appkey');
        }
        if (empty($data['tid'])) {
            show_json(0, '请传模板id');
        }
        if (empty($data['code'])) {
            show_json(0, '请传短信验证码变量');
        }
        if (empty($data['service'])) {
            show_json(0, '请传客服电话');
        }
        if (empty($data['agreement'])) {
            show_json(0, '请传协议');
        }
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
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