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
     * @data id:id name:等级名称 score:分数 number:接单数 status:状态_1开启_2不开启 createtime:创建时间
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
     * @param name:score type:int require:1 default:- other:- desc:分数
     * @param name:number type:int require:1 default:- other:- desc:接单数
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
     * @param name:url type:string require:1 default:- other:- desc:图片url
     * @param name:jumpurl type:string require:0 default:- other:- desc:跳转链接地址
     * @param name:sort type:string require:1 default:- other:- desc:排序(越小越靠前)
     * @param name:type type:string require:1 default:- other:- desc:类型_1首页轮播图_2保险页面图_3新闻页面轮播图
     * @param name:status type:string require:1 default:- other:- desc:状态_1显示_2不显示
     * @param name:newsid type:int require:0 default:- other:- desc:新闻id(type为3时)
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
     * @title 读取
     * @url /admin/grade/:id
     * @method get
     * @return id:id
     * @return url:图片url
     * @return jumpurl:跳转链接地址
     * @return sort:排序(越小越靠前)
     * @return type:类型_1首页轮播图_2保险页面图_3新闻页面轮播图
     * @return type_text:类型文本
     * @return newsid:新闻id(type为3时)
     * @return status:状态_1显示_2不显示
     * @return status_text:状态文本
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Grade();
        $m->GetOne($id);
    }

}