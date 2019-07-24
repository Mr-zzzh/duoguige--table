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
     * @param name:genre type:int require:0 default:- other:- desc:类型_1维修单_2保养单
     * @param name:type type:int require:0 default:- other:- desc:状态_1待审批_2已审批(不传是所有)
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址 status:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 createtime:创建时间 receive_id:接取人id receive_name:接取人姓名 receive_avatar:接单人头像 evaluate:评价(0未评1已评价) image:图片
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
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Maintenance();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 修改维保单状态(取消/完成)-物业
     * @url /maintenance/status_edit
     * @method post
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @param name:status type:int require:1 default:- other:- desc:-1取消4已完成
     * @author 开发者
     */
    public function status_edit() {
        $m = new \app\mobile\model\Maintenance();
        $m->StatusEdit(request()->post());
    }

    /**
     * @title 读取(物业)
     * @url /maintenance/:id
     * @method get
     * @return data:列表@
     * @return plan:进度列表@
     * @return complaint:投诉列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 province:省 city:市编号 area:区编号 location:电梯所在地 detail:详情地址(编辑) address:地址 status:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 receive_id:接单人id receive_time:接单时间 name:发布人姓名 avatar:发布人头像 company_name:发布人公司名称 receive_phone:接取人电话 receive_avatar:接取人头像 receive_name:接取人姓名 receive_company:接取人公司名称 image:图片
     * @plan plan:进度 createtime:时间(倒序)
     * @complaint id:id uid:用户id mid:维保单id content:投诉内容 thumb:投诉图片 createtime:投诉时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Maintenance();
        $m->GetOne($id);
    }

    /**
     * @title 评价(物业)
     * @url /evaluate
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @param name:start type:int require:1 default:- other:- desc:星星数量
     * @param name:content type:string require:1 default:- other:- desc:评价内容
     * @author 开发者
     */
    public function evaluate() {
        $m = new \app\mobile\model\Maintenance();
        $m->Evaluate(request()->param());
    }

    /**
     * @title 投诉(物业)
     * @url /complaint
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @param name:content type:string require:1 default:- other:- desc:投诉内容
     * @param name:thumb type:string require:0 default:- other:- desc:图片(多张用逗号拼接或者数组)
     * @author 开发者
     */
    public function complaint() {
        $m = new \app\mobile\model\Maintenance();
        $m->Complaint(request()->param());
    }

    /**
     * @title 全部评价(物业)
     * @url /allevaluate
     * @method get
     * @param name:id type:int require:0 default:- other:- desc:维修师傅id(物业看传,自己看自己不传)
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @return user:大师信息@
     * @data brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 name:评价人姓名 avatar:评价人头像 start:评价星星 content:评价内容 createtime:0评价时间
     * @user name:姓名 avatar:头像 company_name:公司名
     * @author 开发者
     */
    public function allevaluate() {
        $m = new \app\mobile\model\Maintenance();
        $m->AllEvaluate(request()->get());
    }

    /**
     * @title 任务大厅(技术大师)
     * @url /task_hall
     * @method get
     * @param name:city type:string require:1 default:- other:- desc:城市名称(武汉市)
     * @param name:area type:int require:1 default:- other:- desc:区编码
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址 createtime:创建时间 image:图片
     * @author 开发者
     */
    public function task_hall() {
        $m = new \app\mobile\model\Maintenance();
        $m->TaskHall(request()->get());
    }

    /**
     * @title 任务大厅区列表
     * @url /maintenance/city
     * @method GET
     * @param name:city type:string require:1 default:- other:- desc:城市名称(武汉市)
     * @return data:区列表@
     * @inflist id:id name:城市名 code:城市编码
     * @author 开发者
     */
    public function city() {
        $params = \request()->param();
        $city   = trim($params['city']);
        if (empty($city)) {
            show_json(0, '请传城市名');
        }
        $id   = db('area')->where(array('name' => $city, 'level' => 2))->value('id');
        $list = db('area')->where('pid', $id)->field('id,name,code')->select();
        show_json(1, array('data' => $list));
    }

    /**
     * @title 任务查询(技术大师)
     * @url /inquire
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 status:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址 createtime:领取时间 name:发布人姓名 avatar:发布人头像 company_name:发布人认证公司 image:图片
     * @author 开发者
     */
    public function inquire() {
        $m = new \app\mobile\model\Maintenance();
        $m->Inquire(request()->get());
    }

    /**
     * @title 领取任务(技术大师)
     * @url /draw
     * @method post
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @author 开发者
     */
    public function draw() {
        $m = new \app\mobile\model\Maintenance();
        $m->Draw(request()->post());
    }

    /**
     * @title 更改维保单状态(技术大师)
     * @url /receive_task
     * @method post
     * @param name:type type:int require:1 default:- other:- desc:类型_1接取_2投诉完成
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @author 开发者(接取/投诉完成)
     */
    public function receive_task() {
        $m = new \app\mobile\model\Maintenance();
        $m->ReceiveTask(request()->post());
    }

    /**
     * @title 我的任务/投诉处理(技术大师)
     * @url /my_task
     * @method get
     * @param name:type type:int require:1 default:- other:- desc:类型_1我的任务_2投诉处理(不传为我的任务)
     * @param name:year type:int require:0 default:- other:- desc:时间(Y)-我的任务(type为1)
     * @param name:month type:int require:0 default:- other:- desc:时间(m)-我的任务(type为1)
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @return evaluate:评价数据数组@
     * @data id:id name:发单人姓名(投诉处理) avatar:发单人头像(投诉处理) company_name:发单人公司(投诉处理) brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址 status:0待审_1审核通过_2不通过_3已接单_4已完成_5投诉_6投诉已处理 createtime:创建时间 receive_time:接单时间(我的任务) complaint_time:投诉时间(投诉处理) evaluate:评价数据数组 image:图片
     * @evaluate name:评价人姓名 avatar:评价人头像 start:星星数 content:评价内容 createtime:评价时间
     * @author 开发者
     */
    public function my_task() {
        $m = new \app\mobile\model\Maintenance();
        $m->MyTask(request()->get());
    }

    /**
     * @title 任务详情(技术大师)
     * @url /task_detail
     * @method get
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @return data:列表@
     * @data id:id brand:电梯品牌 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址  phone:发布人手机 name:发布人姓名 avatar:发布人头像 company_name:发布人公司名称 plan:最新进度 image:图片
     * @author 开发者
     */
    public function task_detail() {
        $m = new \app\mobile\model\Maintenance();
        $m->TaskDetail(request()->get());
    }

    /**
     * @title 更新进度(技术大师)
     * @url /plan
     * @method post
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @param name:plan type:string require:1 default:- other:- desc:进度
     * @author 开发者
     */
    public function plan() {
        $m = new \app\mobile\model\Maintenance();
        $m->Plan(request()->post());
    }

    /**
     * @title 投诉详情(技术大师)
     * @url /complaint_detail
     * @method get
     * @param name:id type:int require:1 default:- other:- desc:维保单id
     * @return data:列表@
     * @data id:id brand:电梯品牌 image:图片 model:型号 floor_number:楼层数 type:维修类型 company:单位名称 city:市编号 area:区编号 address:地址  phone:发布人手机 name:发布人姓名 avatar:发布人头像 company_name:发布人公司名称 plan:最新进度 content:投诉内容 thumb:投诉图片
     * @author 开发者
     */
    public function complaint_detail() {
        $m = new \app\mobile\model\Maintenance();
        $m->TcomplaintDetail(request()->get());
    }

}