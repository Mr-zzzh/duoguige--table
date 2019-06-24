<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 维保单管理
 * @group ADMIN
 */
class Maintenance extends Common {

    /**
     * @title 列表
     * @url /admin/maintenance
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @param name:genre type:int require:0 default:- other:- desc:维保单类型_1维修单_2保养单
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id uname:发布人姓名 brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型(急修,维修,保养) company:单位名称 province:省编号 province_text:省 city:市编号 city_text:市 area:区编号 area_text:区 address:地址 genre:类型_1维修单_2保养单 genre_text:类型文本 status:状态-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理  status_text:状态文本 checktime:审核时间 createtime:创建时间 canceltime:取消时间 finishtime:完成时间 receive_id:接取保单师傅id rname:接取师傅姓名 receive_time:接取时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Maintenance();
        $m->GetAll(request()->get());
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
     * @title 读取
     * @url /admin/maintenance/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return uname:发布人姓名
     * @return brand:电梯品牌
     * @return model:型号
     * @return floor_number:楼层数
     * @return type:维修类型(急修, 维修, 保养)
     * @return company:单位名称
     * @return province:省编号
     * @return province_text:省
     * @return city:市编号
     * @return city_text:市
     * @return area:区编号
     * @return area_text:区
     * @return address:地址
     * @return genre:类型_1维修单_2保养单
     * @return genre_text:类型文本
     * @return status:-1取消0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理
     * @return status_text:状态文本
     * @return checktime:审核时间
     * @return canceltime:取消时间
     * @return createtime:创建时间
     * @return finishtime:完成时间
     * @return receive_id:接取保单师傅id
     * @return rname:接取师傅姓名
     * @return receive_time:接取时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Maintenance();
        $m->GetOne($id);
    }

    /**
     * @title 审核
     * @url /admin/maintenance/editstatus
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @param name:status type:int require:1 default:- other:- desc:状态_1通过_2不通过
     * @author 开发者
     */
    public function editstatus() {
        $m = new \app\admin\model\Maintenance();
        $m->EditStatus(request()->param());
    }

}