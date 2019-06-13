<?php

namespace app\admin\model;

use think\Cache;

class Area extends Common {

    public function getCodeList() {
        $newlist = Cache::get('address_code_list');
        if (empty($newlist)) {
            $list    = $this->select()->toArray();
            $newlist = array();
            if (!empty($list)) {
                foreach ($list as $v) {
                    $newlist[$v['code']] = $v['name'];
                }
            }
            unset($list);
            Cache::set('address_code_list', $newlist);
        }
        return $newlist;
    }

    public function getNameList() {
        $newlist = Cache::get('address_name_list');
        if (empty($newlist)) {
            $list    = $this->select()->toArray();
            $newlist = array();
            if (!empty($list)) {
                foreach ($list as $v) {
                    $newlist[$v['level']][$v['name']] = $v['code'];
                }
            }
            unset($list);
            Cache::set('address_name_list', $newlist);
        }
        return $newlist;
    }

    public function citycode($level = 1, $name = '') {
        $list = $this->getNameList();
        return $list[$level][$name];
    }

    public function cityname($code = '') {
        $list = $this->getCodeList();
        return $list[$code];
    }

    public function getlist($code = '') {
        $map          = array();
        $map['pcode'] = intval($code);
        $map['code']  = ['>=', 110000];
        $list         = $this->cache(600)->where($map)->select()->toArray();
        return $list;
    }

    //按字母排序获取澄海市
    public function getcity() {
        $list    = $this->cache(600)->order('firstchar ASC')->where(array('level' => 2, 'code' => ['>=', 110000]))->select()->toArray();
        $newlist = array();
        foreach ($list as $v) {
            $newlist[$v['firstchar']][] = $v;
        }
        unset($list);
        return $newlist;
    }

}