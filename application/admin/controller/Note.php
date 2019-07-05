<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 短信接口设置管理
 * @group ADMIN
 */
class Note extends Common {

    /**
     * @title 列表
     * @url /admin/note
     * @method get
     * @return data:列表@
     * @data id:id appkey:短信appkey tid:模板id code:短信验证码变量 service:客服电话 agreement:协议 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Note();
        $m->GetAll();
    }

    /**
     * @title 添加
     * @url /admin/note
     * @method post
     * @param name:appkey type:string require:1 default:- other:- desc:短信appkey
     * @param name:tid type:string require:0 default:- other:- desc:模板id
     * @param name:code type:string require:1 default:- other:- desc:短信验证码变量
     * @param name:service type:string require:1 default:- other:- desc:客服电话
     * @param name:agreement type:string require:1 default:- other:- desc:协议
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Note();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/note/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Note();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/note/:id
     * @method put
     * @param name:url type:int require:1 default:- other:- desc:图片url
     * @param name:jumpurl type:int require:0 default:- other:- desc:跳转链接地址
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
        $m = new \app\admin\model\Note();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/note/:id
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
        $m = new \app\admin\model\Note();
        $m->GetOne($id);
    }

}