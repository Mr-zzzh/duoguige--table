<?php

namespace app\mobile\model;

class Brand extends Common {

    public function GetAll() {
        $list = $this->field('id,name,logo')->order('createtime desc')->select()->toArray();
        show_json(1, array('data' => $list));
    }
}