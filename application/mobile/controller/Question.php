<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 问答管理
 * @group MOBILE
 */
class Question extends Common {

    /**
     * @title 问题列表
     * @url /question
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id title:问题 thumb:图片(数组) number:回答人数
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Question();
        $m->GetAll(request()->get());
    }

    /**
     * @title 提问
     * @url /question
     * @method post
     * @param name:title type:string require:1 default:- other:- desc:标题
     * @param name:thumb type:string require:1 default:- other:- desc:图片(多张用逗号拼接或者数组)
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Question();
        $m->AddOne(request()->post());
    }

    /**
     * @title 读取
     * @url /question/:id
     * @method get
     * @return id:id
     * @return name:提问人姓名
     * @return avatar:提问人头像
     * @return title:问题
     * @return thumb:问题图片(数组)
     * @return number:回答人数
     * @return createtime:提问时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Question();
        $m->GetOne($id);
    }

    /**
     * @title 答案列表
     * @url /answer
     * @method get
     * @param name:qid type:int require:1 default:- other:- desc:问题id
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id answer:回答 qid:问题id createtime:创建时间 name:回答人姓名 avatar:回答人头像
     * @author 开发者
     */
    public function answer() {
        $m = new \app\mobile\model\Answer();
        $m->GetAll(request()->get());
    }

}