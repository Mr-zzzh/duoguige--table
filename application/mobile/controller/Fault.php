<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 故障库
 * @group MOBILE
 */
class Fault extends Common {

    /**
     * @title 列表
     * @url /fault
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id fault_code:故障代码 bid:品牌id models:使用机型 paraphrase:代码释义 dispose:处理办法 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Fault();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /fault/:id
     * @method get
     * @return id:id
     * @return fault_code:故障代码
     * @return bid:品牌id
     * @return models:使用机型
     * @return paraphrase:代码释义
     * @return dispose:处理办法
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Fault();
        $m->GetOne($id);
    }

}