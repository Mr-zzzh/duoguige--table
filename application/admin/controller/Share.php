<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 分享设置管理
 * @group ADMIN
 */
class Share extends Common {

    /**
     * @title 添加
     * @url /admin/share
     * @method post
     * @param name:title type:string require:1 default:- other:- desc:分享标题
     * @param name:icon type:string require:1 default:- other:- desc:分享图标
     * @param name:intro type:string require:1 default:- other:- desc:分享描述
     * @param name:share_link type:string require:1 default:- other:- desc:分享链接
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Share();
        $m->AddOne(request()->post());
    }

    /**
     * @title 编辑
     * @url /admin/share/:id
     * @method put
     * @param name:title type:string require:1 default:- other:- desc:分享标题
     * @param name:icon type:string require:1 default:- other:- desc:分享图标
     * @param name:intro type:string require:1 default:- other:- desc:分享描述
     * @param name:share_link type:string require:1 default:- other:- desc:分享链接
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Share();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/share/:id
     * @method get
     * @return id:id
     * @return title:分享标题
     * @return icon:分享图标
     * @return intro:分享描述
     * @return share_link:分享链接
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read() {
        $m = new \app\admin\model\Share();
        $m->GetOne();
    }

}