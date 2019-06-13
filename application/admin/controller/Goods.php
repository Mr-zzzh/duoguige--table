<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 商品管理
 * @group ADMIN
 */
class Goods extends Common {

    /**
     * @title 列表
     * @url /admin/goods
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:bid type:string require:0 default:- other:- desc:品牌id
     * @param name:cid type:string require:0 default:- other:- desc:分类id
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:商品名 bid:品牌id bname:品牌名 thumbnail:商品缩略图 manufacturers:厂家名称 phone:销售电话 price:价格 label:标签 sale_number:销量
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Goods();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/goods
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:商品名
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:cid type:int require:1 default:- other:- desc:商品分类id
     * @param name:thumbnail type:string require:1 default:- other:- desc:商品缩略图
     * @param name:image type:string require:1 default:- other:- desc:详情轮播图(传数组或者字符串)
     * @param name:specification type:string require:1 default:- other:- desc:规格
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:manufacturers type:string require:1 default:- other:- desc:厂家名称
     * @param name:phone type:string require:1 default:- other:- desc:销售电话
     * @param name:price type:float require:1 default:- other:- desc:价格
     * @param name:label type:string require:1 default:- other:- desc:标签(传数组或者字符串)
     * @param name:intro type:string require:1 default:- other:- desc:详情
     * @param name:area type:string require:1 default:- other:- desc:产地
     * @param name:province type:string require:1 default:- other:- desc:省份
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:color type:string require:1 default:- other:- desc:颜色
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Goods();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/goods/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Goods();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/goods/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:商品名
     * @param name:bid type:int require:1 default:- other:- desc:品牌id
     * @param name:cid type:int require:1 default:- other:- desc:商品分类id
     * @param name:thumbnail type:string require:1 default:- other:- desc:商品缩略图
     * @param name:image type:string require:1 default:- other:- desc:详情轮播图(传数组或者字符串)
     * @param name:specification type:string require:1 default:- other:- desc:规格
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:manufacturers type:string require:1 default:- other:- desc:厂家名称
     * @param name:phone type:string require:1 default:- other:- desc:销售电话
     * @param name:price type:float require:1 default:- other:- desc:价格
     * @param name:label type:string require:1 default:- other:- desc:标签(传数组或者字符串)
     * @param name:intro type:string require:1 default:- other:- desc:详情
     * @param name:area type:string require:1 default:- other:- desc:产地
     * @param name:province type:string require:1 default:- other:- desc:省份
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:color type:string require:1 default:- other:- desc:颜色
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Goods();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/goods/:id
     * @method get
     * @return id:id
     * @return name:商品名
     * @return bid:品牌id
     * @return bname:品牌名
     * @return cid:商品分类id
     * @return cname:商品分类名
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
        $m = new \app\admin\model\Goods();
        $m->GetOne($id);
    }

}