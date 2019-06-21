<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 新闻管理
 * @group MOBILE
 */
class News extends Common {

    /**
     * @title 列表
     * @url /news
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:头部轮播图@
     * @data id:id url:图片url jumpurl:跳转地址 newsid:新闻id
     * @return data:列表@
     * @data id:id title:标题 thumb:图片 view_number:浏览量 like_number:点赞量 trype:类型_1图文_2视频
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\News();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /news/:id
     * @method get
     * @return id:id
     * @return title:标题
     * @return thumb:图片
     * @return type:类型1图文2视频
     * @return video:视频链接
     * @return content:内容
     * @return view_number:浏览量
     * @return like_number:点赞量
     * @return comment_number:评论数量
     * @return sort:排序(序号越小越靠前)
     * @return status:状态_1显示_2不显示
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\News();
        $m->GetOne($id);
    }

    /**
     * @title 列表
     * @url /leavemessage
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:type type:int require:0 default:- other:- desc:1新闻留言2视频留言
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id nid:新闻或视频id type:1新闻留言2视频留言 content:留言内容 like_number:点赞数量 createtime:创建时间
     * @author 开发者
     */
    public function leavemessage() {
        $m = new \app\mobile\model\LeaveMessage();
        $m->GetAll(request()->get());
    }

}