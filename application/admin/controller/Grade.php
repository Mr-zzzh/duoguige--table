<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 技师等级管理
 * @group ADMIN
 */
class Grade extends Common {

    /**
     * @title 列表
     * @url /admin/grade
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @return content:晋级条件@
     * @data id:id name:规则名称 status:状态_1开启_2不开启 createtime:创建时间
     * @content name:等级名称 min_score:最小分数 max_score:最大分数 min_number:最小接单数 max_number:最大接单数
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Grade();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/grade
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:等级名称
     * @param name:content type:array require:1 default:- other:- desc:晋级条件(数组)name_等级名称min_score_最小分数max_score_最大分数min_number_最小接单数max_number_最大接单数
     * @param name:status type:string require:1 default:- other:- desc:状态_1开启_2不开启
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Grade();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/grade/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Grade();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/grade/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:等级名称
     * @param name:score type:int require:1 default:- other:- desc:分数
     * @param name:number type:int require:1 default:- other:- desc:接单数
     * @param name:status type:string require:1 default:- other:- desc:状态_1开启_2不开启
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Grade();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 使用
     * @url /admin/grade/:id
     * @method get
     * @param name:status type:string require:1 default:- other:- desc:状态_1开启_2不开启
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $status = intval(request()->param('status'));
        if (empty($status)) {
            show_json(0, '请传状态');
        }
        $m = new \app\admin\model\Grade();
        $m->GetOne($id, $status);
    }

}