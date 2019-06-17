<?php

namespace app\mobile\model;

class Brand extends Common {

    public function GetAll() {
        $list = $this->field('id,name,logo')->select()->toArray();
        show_json(1, $list);
    }
}