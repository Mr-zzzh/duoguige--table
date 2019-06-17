<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 品牌资料库
 * @group MOBILE
 */
class Brand extends Common {

    /**
     * @title 品牌列表
     * @url /brand
     * @method get
     * @return data:列表@
     * @data id:id name:品牌名 logo:logo createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Brand();
        $m->GetAll();
    }

    /**
     * @title 品牌资料列表
     * @url /branddatum
     * @method get
     * @param name:bid type:string require:1 default:- other:- desc:品牌id
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:资料标题 size:大小 view:浏览量 download:下载量
     * @author 开发者
     */
    public function branddatum() {
        $m = new \app\mobile\model\BrandDatum();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /brand/:id
     * @method get
     * @return datum:资料链接地址
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