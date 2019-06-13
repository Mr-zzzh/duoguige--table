<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title JobWanted
 * @group MOBILE
 */
class JobWanted extends Common {

    /**
     * @title 列表
     * @url /jobwanted
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:状态_0待审_1通过_2不通过_3已找到工作
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id post:求职岗位 salary:期望薪资 arrival:到岗时间 province:省编号 city:市编号 intro:自我描述 status:状态_0待审_1通过_2不通过_3已找到工作 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\JobWanted();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /jobwanted/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /jobwanted
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
     * @return uid:用户id
     * @return post:求职岗位
     * @return salary:期望薪资
     * @return arrival:到岗时间
     * @return province:省编号
     * @return city:市编号
     * @return intro:自我描述
     * @return status:状态_0待审_1通过_2不通过_3已找到工作
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