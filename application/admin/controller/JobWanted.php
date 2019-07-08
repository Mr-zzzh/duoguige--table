<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 求职管理
 * @group ADMIN
 */
class JobWanted extends Common {

    /**
     * @title 列表
     * @url /admin/jobwanted
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:状态_0待审_1通过_2不通过_3已找到工作
     * @param name:salary type:string require:0 default:- other:- desc:薪资范围id
     * @param name:province type:string require:0 default:- other:- desc:省编号
     * @param name:city type:string require:0 default:- other:- desc:市编号
     * @param name:area type:string require:0 default:- other:- desc:区编号
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id uname:发布人昵称 post:求职岗位 name:求职者姓名 education:最高学历 salary:期望薪资 sname:薪资范围文本 arrival:到岗时间 province:省编号 province_text:省 city:市编号 city_text:市 area:区编号 area_text:区 address:详细地址 intro:自我描述 status:状态_0待审_1通过_2不通过_3已找到工作  status_text:状态文本 createtime:创建时间 checktime:审核时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\JobWanted();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/jobwanted
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:post type:string require:1 default:- other:- desc:求职岗位
     * @param name:salary type:string require:1 default:- other:- desc:期望薪资
     * @param name:arrival type:string require:1 default:- other:- desc:到岗时间
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:intro type:string require:1 default:- other:- desc:自我描述
     * @param name:status type:int require:1 default:- other:- desc:状态_0待审_1通过_2不通过_3已找到工作
     * @author 开发者
     */
    /* public function save() {
         $m = new \app\admin\model\JobWanted();
         $m->AddOne(request()->post());
     }*/

    /**
     * @title 删除
     * @url /admin/jobwanted/:id
     * @method delete
     * @author 开发者
     */
    /* public function delete($id) {
         if ($id < 1) {
             show_json(0, '参数ID错误');
         }
         $m = new \app\admin\model\JobWanted();
         $m->DelOne($id);
     }*/

    /**
     * @title 编辑
     * @url /admin/jobwanted/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:post type:string require:1 default:- other:- desc:求职岗位
     * @param name:salary type:string require:1 default:- other:- desc:期望薪资
     * @param name:arrival type:string require:1 default:- other:- desc:到岗时间
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:intro type:string require:1 default:- other:- desc:自我描述
     * @param name:status type:int require:1 default:- other:- desc:状态_0待审_1通过_2不通过_3已找到工作
     * @author 开发者
     */
    /*public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\JobWanted();
        $m->EditOne($request->put(), $id);
    }*/

    /**
     * @title 读取
     * @url /admin/jobwanted/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return uname:发布人昵称
     * @return post:求职岗位
     * @return education:最高学历
     * @return name:求职者姓名
     * @return salary:期望薪资
     * @return sname:期望薪资文本
     * @return arrival:到岗时间
     * @return province:省编号
     * @return province_text:省
     * @return city:市编号
     * @return province_text:市
     * @return area:区编号
     * @return area_text:区
     * @return address:详细地址
     * @return intro:自我描述
     * @return status:状态_0待审_1通过_2不通过_3已找到工作
     * @return status_text:状态文本
     * @return createtime:创建时间
     * @return createtime:审核时间
     * @return remark:审核备注
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\JobWanted();
        $m->GetOne($id);
    }

    /**
     * @title 审核
     * @url /admin/jobwanted/editstatus
     * @method post|get
     * @param name:id type:int require:1 default:- other:- desc:招聘信息id
     * @param name:status type:int require:1 default:- other:- desc:状态_1通过_2不通过
     * @param name:remark type:string require:0 default:- other:- desc:审核备注
     * @author 开发者
     */
    public function editstatus() {
        $m = new \app\admin\model\JobWanted();
        $m->EditStatus(request()->param());
    }

}