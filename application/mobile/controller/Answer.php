<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title Answer
 * @group MOBILE
 */
class Answer extends Common {

    /**
     * @title 列表
     * @url /answer
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:状态_1显示_2隐藏
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uid:用户id answer:回答 qid:问题id status:状态_1显示_2隐藏 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Answer();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /answer/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /answer
     * @method post
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:answer type:string require:1 default:- other:- desc:回答
     * @param name:qid type:int require:1 default:- other:- desc:问题id
     * @param name:status type:int require:1 default:- other:- desc:状态_1显示_2隐藏
     * @author 开发者
     */
    public function save() {
        $m = new \app\mobile\model\Answer();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /answer/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Answer();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /answer/:id
     * @method put
     * @param name:uid type:int require:1 default:- other:- desc:用户id
     * @param name:answer type:string require:1 default:- other:- desc:回答
     * @param name:qid type:int require:1 default:- other:- desc:问题id
     * @param name:status type:int require:1 default:- other:- desc:状态_1显示_2隐藏
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Answer();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /answer/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return answer:回答
     * @return qid:问题id
     * @return status:状态_1显示_2隐藏
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Answer();
        $m->GetOne($id);
    }

}