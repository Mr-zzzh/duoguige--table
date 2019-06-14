<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 意见反馈
 * @group ADMIN
 */
class Feedback extends Common {

    /**
     * @title 列表
     * @url /admin/feedback
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户ID uname:反馈人姓名 content:反馈内容 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Feedback();
        $m->GetAll(request()->get());
    }

    /**
     * @title 删除
     * @url /admin/feedback/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Feedback();
        $m->DelOne($id);
    }
}