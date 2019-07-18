<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title APP版本设置管理
 * @group ADMIN
 */
class Version extends Common {

    /**
     * @title 编辑
     * @url /admin/version/:id
     * @method put
     * @param name:ios_new_version type:string require:1 default:- other:- desc:ios最新版本
     * @param name:ios_min_version type:string require:1 default:- other:- desc:ios最小兼容版本
     * @param name:android_new_version type:string require:1 default:- other:- desc:android最新版本
     * @param name:android_min_version type:string require:1 default:- other:- desc:android最小兼容版本
     * @param name:ios_url type:string require:1 default:- other:- desc:ios下载地址
     * @param name:android_url type:string require:1 default:- other:- desc:android地址
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Version();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/version/:id
     * @method get
     * @return id:id
     * @return ios_new_version:ios最新版本
     * @return ios_min_version:ios最小兼容版本
     * @return android_new_version:android最新版本
     * @return android_min_version:android最小兼容版本
     * @return ios_url:ios下载地址
     * @return android_url:android地址
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read() {
        $m = new \app\admin\model\Version();
        $m->GetOne();
    }

}