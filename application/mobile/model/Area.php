<?php

namespace app\mobile\model;

class Area extends Common {

    public function pca_tree() {
        $tree = cache('pca_tree');
        if (empty($tree)) {
            $list = $this->field('id,name,pid')->select()->toArray();
            $tree = list_to_tree($list);
            cache('pca_tree', $tree);
        }
        return $tree;
    }

    public function GetAll() {
        show_json(1, $this->pca_tree());
    }
}