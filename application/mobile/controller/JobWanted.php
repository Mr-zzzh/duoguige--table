<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 求职管理
 * @group MOBILE
 */
class JobWanted extends Common {

    /**
     * @title 列表
     * @url /jobwanted
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:salary type:int require:0 default:- other:- desc:薪资范围id
     * @param name:province type:int require:0 default:- other:- desc:省编号
     * @param name:city type:int require:0 default:- other:- desc:市编号
     * @param name:area type:int require:0 default:- other:- desc:区编号
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id post:求职岗位 salary_text:期望薪资 intro:自我描述 name:姓名 phone:联系方式 avatar:头像 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\JobWanted();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /jobwanted
     * @method post
     * @param name:post type:string require:1 default:- other:- desc:求职岗位
     * @param name:salary type:string require:1 default:- other:- desc:期望薪资
     * @param name:arrival type:string require:1 default:- other:- desc:到岗时间
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:area type:int require:1 default:- other:- desc:区编号
     * @param name:intro type:string require:1 default:- other:- desc:自我描述
     * @param name:education type:string require:1 default:- other:- desc:最高学历
     * @param name:name type:string require:1 default:- other:- desc:姓名
     * @param name:address type:string require:1 default:- other:- desc:详细地址
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\JobWanted();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /jobwanted/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\JobWanted();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /jobwanted/:id
     * @method put
     * @param name:post type:string require:1 default:- other:- desc:求职岗位
     * @param name:salary type:string require:1 default:- other:- desc:期望薪资
     * @param name:arrival type:string require:1 default:- other:- desc:到岗时间
     * @param name:province type:int require:1 default:- other:- desc:省编号
     * @param name:city type:int require:1 default:- other:- desc:市编号
     * @param name:area type:int require:1 default:- other:- desc:区编号
     * @param name:intro type:string require:1 default:- other:- desc:自我描述
     * @param name:education type:string require:1 default:- other:- desc:最高学历
     * @param name:name type:string require:1 default:- other:- desc:姓名
     * @param name:address type:string require:1 default:- other:- desc:详细地址
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\JobWanted();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /jobwanted/:id
     * @method get
     * @return id:id
     * @return post:求职岗位
     * @return education:最高学历
     * @return name:求职者姓名
     * @return salary_text:期望薪资
     * @return arrival:到岗时间
     * @return province:省编号
     * @return province_text:省
     * @return city:市编号
     * @return province_text:市
     * @return area:区编号
     * @return area_text:区
     * @return address:详细地址
     * @return intro:自我描述
     * @return avatar:头像
     * @return phone:联系方式
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\JobWanted();
        $m->GetOne($id);
    }

}