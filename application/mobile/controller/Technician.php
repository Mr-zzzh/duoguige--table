<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 大师管理
 * @group MOBILE
 */
class Technician extends Common {

    /**
     * @title 大师列表
     * @url /technician
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:用户id name:真实姓名 avatar:头像 phone:手机号 label:已认证维修大师
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Technician();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /technician/:id
     * @method get
     * @return id:用户id
     * @return name:真实姓名
     * @return avatar:头像
     * @return phone:电话
     * @return intro:简介
     * @return label:已认证维修大师
     * @return number:问题数量
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Technician();
        $m->GetOne($id);
    }

    /**
     * @title 问答列表
     * @url /technician/question
     * @method get
     * @param name:id type:string require:0 default:- other:- desc:大师id
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:问题id title:问题标题 thumb:问题图片 answer:答案
     * @author 开发者
     */
    public function question() {
        $m = new \app\mobile\model\Technician();
        $m->Question(request()->get());
    }

    /**
     * @title 向大师提问
     * @url /technician/question_add
     * @method post
     * @param name:master_id type:string require:1 default:- other:- desc:大师id
     * @param name:title type:string require:1 default:- other:- desc:标题
     * @param name:thumb type:string require:1 default:- other:- desc:图片(多张用逗号拼接或者数组)
     * @author 开发者
     */
    public function question_add() {
        $m = new \app\mobile\model\Technician();
        $m->QuestionAdd(request()->post());
    }

}