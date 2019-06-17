<?php

namespace app\mobile\model;

class Feedback extends Common {

    public function AddOne($params) {
        global $member;
        if (check_often(request()->controller() . '_' . request()->action() . '_' . $member['id'])) {
            show_json(0, '请勿频繁操作');
        }
        if (empty($params['content'])) {
            show_json(0, '反馈内容不能为空');
        }
        $data = array(
            'uid'        => $member['id'],
            'content'    => trim($params['content']),
            'createtime' => time(),
        );
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

}