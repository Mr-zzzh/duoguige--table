<?php

namespace app\admin\model;

class Note extends Common {
    public function EditOne($params, $id) {
        $data = array(
            'appkey'    => trim($params['appkey']),
            'appsecret' => trim($params['appsecret']),
            'sign'      => trim($params['sign']),
            'code'      => trim($params['code']),
            'service'   => trim($params['service']),
            'agreement' => trim($params['agreement']),
        );
        if (empty($data['appkey'])) {
            show_json(0, '请传短信appkey');
        }
        if (empty($data['appsecret'])) {
            show_json(0, '请传短信appsecret');
        }
        if (empty($data['sign'])) {
            show_json(0, '请传签名');
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
        $id = $this->order('createtime desc')->limit(1)->value('id');
        if (empty($id)) {
            $data['createtime'] = time();
            if ($this->insert($data)) {
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