<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title Maintenance
 * @group ADMIN
 */
class Maintenance extends Common {

    /**
     * @title 列表
     * @url /admin/maintenance
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @param name:type type:int require:0 default:- other:- desc:维修类型
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 address:地址 status:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 star:打星 evaluate:评价 complain:投诉 complain_image:投诉照片 checktime:审核时间 canceltime:取消时间 createtime:创建时间 finishtime:完成时间 evaluate_time:评价时间 complain_time:投诉时间 complain_finish_time:投诉完成时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Maintenance();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /admin/maintenance/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /admin/maintenance
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:floor_number type:int require:1 default:- other:- desc:楼层数
     * @param name:type type:string require:1 default:- other:- desc:维修类型
     * @param name:company type:string require:1 default:- other:- desc:单位名称
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:status type:int require:1 default:- other:- desc:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @param name:star type:int require:1 default:- other:- desc:打星
     * @param name:evaluate type:string require:1 default:- other:- desc:评价
     * @param name:complain type:string require:1 default:- other:- desc:投诉
     * @param name:complain_image type:string require:1 default:- other:- desc:投诉照片
     * @param name:checktime type:int require:1 default:- other:- desc:审核时间
     * @param name:canceltime type:int require:1 default:- other:- desc:取消时间
     * @param name:finishtime type:int require:1 default:- other:- desc:完成时间
     * @param name:evaluate_time type:int require:1 default:- other:- desc:评价时间
     * @param name:complain_time type:int require:1 default:- other:- desc:投诉时间
     * @param name:complain_finish_time type:int require:1 default:- other:- desc:投诉完成时间
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Maintenance();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/maintenance/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Maintenance();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/maintenance/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:floor_number type:int require:1 default:- other:- desc:楼层数
     * @param name:type type:string require:1 default:- other:- desc:维修类型
     * @param name:company type:string require:1 default:- other:- desc:单位名称
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @param name:status type:int require:1 default:- other:- desc:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @param name:star type:int require:1 default:- other:- desc:打星
     * @param name:evaluate type:string require:1 default:- other:- desc:评价
     * @param name:complain type:string require:1 default:- other:- desc:投诉
     * @param name:complain_image type:string require:1 default:- other:- desc:投诉照片
     * @param name:checktime type:int require:1 default:- other:- desc:审核时间
     * @param name:canceltime type:int require:1 default:- other:- desc:取消时间
     * @param name:finishtime type:int require:1 default:- other:- desc:完成时间
     * @param name:evaluate_time type:int require:1 default:- other:- desc:评价时间
     * @param name:complain_time type:int require:1 default:- other:- desc:投诉时间
     * @param name:complain_finish_time type:int require:1 default:- other:- desc:投诉完成时间
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Maintenance();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/maintenance/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return brand:电梯品牌
     * @return model:型号
     * @return floor_number:楼层数
     * @return type:维修类型
     * @return company:单位名称
     * @return address:地址
     * @return status:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @return star:打星
     * @return evaluate:评价
     * @return complain:投诉
     * @return complain_image:投诉照片
     * @return checktime:审核时间
     * @return canceltime:取消时间
     * @return createtime:创建时间
     * @return finishtime:完成时间
     * @return evaluate_time:评价时间
     * @return complain_time:投诉时间
     * @return complain_finish_time:投诉完成时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Maintenance();
        $m->GetOne($id);
    }

}