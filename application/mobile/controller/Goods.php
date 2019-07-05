<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 商品管理
 * @group MOBILE
 */
class Goods extends Common {

    /**
     * @title 商品分类列表
     * @url /goodscate
     * @method get
     * @return data:列表@
     * @data id:id name:商品分类名
     * @author 开发者
     */
    public function goodscate() {
        $list = db('goods_cate')->field('id,name')->select();
        show_json(1, array('data' => $list));
    }

    /**
     * @title 商品列表
     * @url /goods
     * @method get
     * @param name:cid type:int require:0 default:- other:- desc:商品分类id
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:商品名 thumbnail:商品缩略图 price:价格
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Goods();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /goods/:id
     * @method get
     * @return id:id
     * @return name:商品名
     * @return subhead:副标题
     * @return sort:排序(越小越靠前)
     * @return bid:品牌id
     * @return bname:品牌名
     * @return cid:商品分类id
     * @return thumbnail:商品缩略图
     * @return image:详情轮播图(数组)
     * @return specification:规格
     * @return model:型号
     * @return manufacturers:厂家名称
     * @return phone:销售电话
     * @return price:价格
     * @return label:标签(数组)
     * @return intro:详情
     * @return area:产地
     * @return province:省份
     * @return address:地址
     * @return color:颜色
     * @return sale_number:销售数量
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