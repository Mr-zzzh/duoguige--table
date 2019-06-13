<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title BrandDatum
 * @group MOBILE
 */
class BrandDatum extends Common {

    /**
     * @title 列表
     * @url /branddatum
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
     * @data id:id bid:品牌id datum:资料路由 size:大小 view:浏览量 download:下载量 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\BrandDatum();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /branddatum/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /branddatum
     * @method post
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:datum type:string require:1 default:- other:- desc:资料路由
     * @param name:size type:string require:1 default:- other:- desc:大小
     * @param name:view type:int require:1 default:- other:- desc:浏览量
     * @param name:download type:int require:1 default:- other:- desc:下载量
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\BrandDatum();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /branddatum/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\BrandDatum();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /branddatum/:id
     * @method put
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:datum type:string require:1 default:- other:- desc:资料路由
     * @param name:size type:string require:1 default:- other:- desc:大小
     * @param name:view type:int require:1 default:- other:- desc:浏览量
     * @param name:download type:int require:1 default:- other:- desc:下载量
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\BrandDatum();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /branddatum/:id
     * @method get
     * @return id:id
     * @return bid:品牌id
     * @return datum:资料路由
     * @return size:大小
     * @return view:浏览量
     * @return download:下载量
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\BrandDatum();
        $m->GetOne($id);
    }

}