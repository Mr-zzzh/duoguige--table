<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title LeaveMessage
 * @group MOBILE
 */
class LeaveMessage extends Common {

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
    public function index() {
        $m = new \app\mobile\model\LeaveMessage();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /leavemessage/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /leavemessage
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:nid type:int require:1 default:- other:- desc:新闻或视频id
     * @param name:type type:int require:1 default:- other:- desc:1新闻留言2视频留言
     * @param name:content type:string require:1 default:- other:- desc:留言内容
     * @param name:like_number type:int require:1 default:- other:- desc:点赞数量
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\LeaveMessage();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /leavemessage/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\LeaveMessage();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /leavemessage/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:nid type:int require:1 default:- other:- desc:新闻或视频id
     * @param name:type type:int require:1 default:- other:- desc:1新闻留言2视频留言
     * @param name:content type:string require:1 default:- other:- desc:留言内容
     * @param name:like_number type:int require:1 default:- other:- desc:点赞数量
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\LeaveMessage();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /leavemessage/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return nid:新闻或视频id
     * @return type:1新闻留言2视频留言
     * @return content:留言内容
     * @return like_number:点赞数量
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\LeaveMessage();
        $m->GetOne($id);
    }

}