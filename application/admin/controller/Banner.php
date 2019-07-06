<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 轮播图管理
 * @group ADMIN
 */
class Banner extends Common {

    /**
     * @title 列表
     * @url /admin/banner
     * @method get
     * @param name:type type:int require:0 default:- other:- desc:类型_1首页轮播图_2保险页面图_3新闻页面轮播图
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id url:图片url jumpurl:跳转链接地址 sort:排序(越小越靠前) type:类型_1首页轮播图_2保险页面图_3新闻页面轮播图 type_text:类型文本 status:状态_1显示_2不显示 status_text:状态文本 createtime:创建时间 newsid:新闻id(type为3时)
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Banner();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/banner
     * @method post
     * @param name:url type:string require:1 default:- other:- desc:图片url
     * @param name:jumpurl type:string require:0 default:- other:- desc:跳转链接地址
     * @param name:sort type:string require:1 default:- other:- desc:排序(越小越靠前)
     * @param name:type type:string require:1 default:- other:- desc:类型_1首页轮播图_2保险页面图_3新闻页面轮播图
     * @param name:status type:string require:1 default:- other:- desc:状态_1显示_2不显示
     * @param name:newsid type:int require:0 default:- other:- desc:新闻id(type为3时)
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Banner();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/banner/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Banner();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/banner/:id
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
        $m = new \app\admin\model\Banner();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/banner/:id
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
        $m = new \app\admin\model\Banner();
        $m->GetOne($id);
    }

}