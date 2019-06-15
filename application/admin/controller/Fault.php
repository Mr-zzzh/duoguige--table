<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 故障库
 * @group ADMIN
 */
class Fault extends Common {

    /**
     * @title 列表
     * @url /admin/fault
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:bid type:string require:0 default:- other:- desc:品牌id
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id fault_code:故障代码 bid:品牌id bname:品牌名 models:适用机型 paraphrase:代码释义 dispose:处理办法 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Fault();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/fault
     * @method post
     * @param name:fault_code type:string require:1 default:- other:- desc:故障代码
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:models type:string require:1 default:- other:- desc:适用机型
     * @param name:paraphrase type:string require:1 default:- other:- desc:代码释义
     * @param name:dispose type:string require:1 default:- other:- desc:处理办法
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Fault();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/fault/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Fault();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/fault/:id
     * @method put
     * @param name:fault_code type:string require:1 default:- other:- desc:故障代码
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:models type:string require:1 default:- other:- desc:适用机型
     * @param name:paraphrase type:string require:1 default:- other:- desc:代码释义
     * @param name:dispose type:string require:1 default:- other:- desc:处理办法
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Fault();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/fault/:id
     * @method get
     * @return id:id
     * @return fault_code:故障代码
     * @return bid:品牌id
     * @return bname:品牌名
     * @return models:适用机型
     * @return paraphrase:代码释义
     * @return dispose:处理办法
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Fault();
        $m->GetOne($id);
    }

}