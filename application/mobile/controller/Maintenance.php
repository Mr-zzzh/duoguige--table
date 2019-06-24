<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 维保单管理
 * @group MOBILE
 */
class Maintenance extends Common {

    /**
     * @title 维保单列表(物业)
     * @url /maintenance
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 province:省编号 city:市编号 area:区编号 address:地址 status:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 createtime:创建时间 receive_id:接取人id receive_name:接取人姓名 evaluate:评价(0未评1已评价)
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Maintenance();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加(维保单)
     * @url /maintenance
     * @method post
     * @param name:genre type:int require:1 default:- other:- desc:类型_1维修单_2保养单
     * @param name:brand type:string require:1 default:- other:- desc:电梯品牌
     * @param name:model type:string require:1 default:- other:- desc:型号
     * @param name:floor_number type:int require:1 default:- other:- desc:楼层数
     * @param name:type type:string require:1 default:- other:- desc:维修类型
     * @param name:company type:string require:1 default:- other:- desc:单位名称
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:area type:int require:1 default:- other:- desc:区编号
     * @param name:address type:string require:1 default:- other:- desc:地址
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Maintenance();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /maintenance/:id
     * @method delete
     * @author 开发者
     */
    /*public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Maintenance();
        $m->DelOne($id);
    }*/

    /**
     * @title 编辑
     * @url /maintenance/:id
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
    /*public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Maintenance();
        $m->EditOne($request->put(), $id);
    }*/

    /**
     * @title 读取
     * @url /maintenance/:id
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
        $m = new \app\mobile\model\Maintenance();
        $m->GetOne($id);
    }

}