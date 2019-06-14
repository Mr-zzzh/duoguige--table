<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 问答管理
 * @group ADMIN
 */
class Question extends Common {

    /**
     * @title 问题列表
     * @url /admin/question
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
     * @data id:id uid:提问人id uname:提问人姓名 title:标题 thumb:图片(数组) createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Question();
        $m->GetAll(request()->get());
    }

    /**
     * @title 删除
     * @url /admin/question/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Question();
        $m->DelOne($id);
    }

    /**
     * @title 读取
     * @url /admin/question/:id
     * @method get
     * @return id:id
     * @return uid:提问人id
     * @return title:标题
     * @return thumb:图片(多张用逗号拼接)
     * @return type:提问类型_1问答模块2大师提问
     * @return master_id:大师id(type为2时必填)
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Question();
        $m->GetOne($id);
    }

}