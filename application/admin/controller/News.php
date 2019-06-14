<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 新闻管理
 * @group ADMIN
 */
class News extends Common {

    /**
     * @title 列表
     * @url /admin/news
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
     * @data id:id title:标题 thumb:图片 content:内容 view_number:浏览量 like_number:点赞量 sort:排序(序号越小越靠前) status:状态_1显示_2不显示 status_text:状态文本 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\News();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/news
     * @method post
     * @param name:title type:string require:1 default:- other:- desc:标题
     * @param name:thumb type:string require:1 default:- other:- desc:图片
     * @param name:content type:string require:1 default:- other:- desc:内容
     * @param name:view_number type:int require:1 default:- other:- desc:浏览量
     * @param name:like_number type:int require:1 default:- other:- desc:点赞量
     * @param name:sort type:int require:1 default:- other:- desc:排序(序号越小越靠前)
     * @param name:status type:int require:1 default:- other:- desc:状态_1显示_2不显示
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\News();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/news/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\News();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/news/:id
     * @method put
     * @param name:title type:string require:1 default:- other:- desc:标题
     * @param name:thumb type:string require:1 default:- other:- desc:图片
     * @param name:content type:string require:1 default:- other:- desc:内容
     * @param name:view_number type:int require:1 default:- other:- desc:浏览量
     * @param name:like_number type:int require:1 default:- other:- desc:点赞量
     * @param name:sort type:int require:1 default:- other:- desc:排序(序号越小越靠前)
     * @param name:status type:int require:1 default:- other:- desc:状态_1显示_2不显示
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\News();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/news/:id
     * @method get
     * @return id:id
     * @return title:标题
     * @return thumb:图片
     * @return content:内容
     * @return view_number:浏览量
     * @return like_number:点赞量
     * @return sort:排序(序号越小越靠前)
     * @return status:状态_1显示_2不显示
     * @return status_text:状态文本
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\News();
        $m->GetOne($id);
    }

}