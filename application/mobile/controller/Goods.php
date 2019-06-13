<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title Goods
 * @group MOBILE
 */
class Goods extends Common {

    /**
     * @title 列表
     * @url /goods
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
     * @data id:id name:商品名 bid:品牌id cid:商品分类id thumbnail:商品缩略图 image:详情轮播图 specification:规格 model:型号 manufacturers:厂家名称 phone:销售电话 price:价格 label:标签 intro:详情 address:地址 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Goods();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /goods/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /goods
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:商品名
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:cid type:int require:1 default:- other:- desc:商品分类id
     * @param name:thumbnail type:string require:1 default:- other:- desc:商品缩略图
     * @param name:image type:string require:1 default:- other:- desc:详情轮播图
     * @param name:specification type:string require:1 default:- other:- desc:规格
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:manufacturers type:string require:1 default:- other:- desc:厂家名称
     * @param name:phone type:string require:1 default:- other:- desc:销售电话
     * @param name:price type:float require:1 default:- other:- desc:价格
     * @param name:label type:string require:1 default:- other:- desc:标签
     * @param name:intro type:string require:1 default:- other:- desc:详情
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Goods();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /goods/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Goods();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /goods/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:商品名
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:cid type:int require:1 default:- other:- desc:商品分类id
     * @param name:thumbnail type:string require:1 default:- other:- desc:商品缩略图
     * @param name:image type:string require:1 default:- other:- desc:详情轮播图
     * @param name:specification type:string require:1 default:- other:- desc:规格
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:manufacturers type:string require:1 default:- other:- desc:厂家名称
     * @param name:phone type:string require:1 default:- other:- desc:销售电话
     * @param name:price type:float require:1 default:- other:- desc:价格
     * @param name:label type:string require:1 default:- other:- desc:标签
     * @param name:intro type:string require:1 default:- other:- desc:详情
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Goods();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /goods/:id
     * @method get
     * @return id:id
     * @return name:商品名
     * @return bid:品牌id
     * @return cid:商品分类id
     * @return thumbnail:商品缩略图
     * @return image:详情轮播图
     * @return specification:规格
     * @return model:型号
     * @return manufacturers:厂家名称
     * @return phone:销售电话
     * @return price:价格
     * @return label:标签
     * @return intro:详情
     * @return address:地址
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Goods();
        $m->GetOne($id);
    }

}