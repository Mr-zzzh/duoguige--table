<?php

namespace app\admin\model;

class Version extends Common {
    public function EditOne($params, $id) {
        $data = array(
            'ios_new_version'     => trim($params['appkey']),
            'ios_min_version'     => trim($params['appsecret']),
            'android_new_version' => trim($params['sign']),
            'android_min_version' => trim($params['code']),
            'ios_url'             => trim($params['service']),
            'android_url'         => trim($params['agreement']),
        );
        if (empty($data['ios_new_version'])) {
            show_json(0, 'ios最新版本');
        }
        if (empty($data['ios_min_version'])) {
            show_json(0, 'ios最小兼容版本');
        }
        if (empty($data['android_new_version'])) {
            show_json(0, 'android最新版本');
        }
        if (empty($data['android_min_version'])) {
            show_json(0, 'android最小兼容版本');
        }
        if (empty($data['ios_url'])) {
            show_json(0, 'ios下载地址');
        }
        if (empty($data['android_url'])) {
            show_json(0, 'android地址');
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