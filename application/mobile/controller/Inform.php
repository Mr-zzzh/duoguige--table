<?php

namespace app\mobile\controller;

use think\Request;

/**
 * @title 审核通知管理
 * @group MOBILE
 */
class Inform extends Common {

    /**
     * @title 未读通知数量获取接口
     * @url /inform/unreadnum
     * @method get
     * @return total:未读消息总数(大于0有未读,0没有)
     * @author 开发者
     */
    public function unreadnum() {
        $m     = new \app\mobile\model\Inform();
        $total = $m->unreadnum();
        if ($total === false) {
            show_json(0, $m->getError());
        }
        show_json(1, array('data' => $total));
    }

    /**
     * @title 列表
     * @url /inform
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id title:标题 read:0未阅_1已阅 createtime:创建时间
     * @author 开发者
     */
    public function index() {
        $m = new \app\mobile\model\Inform();
        $m->GetAll(request()->get());
    }

    /**
     * @title 读取
     * @url /inform/:id
     * @method get
     * @return id:id
     * @return uid:用户id
     * @return title:标题
     * @return status:审核状态_1通过_2不通过
     * @return content:审核备注
     * @return type:类型_1公司认证_2技师认证_3维保单审核_4招聘审核_5求职审核
     * @return checkid:审核id
     * @return read:0未阅_1已阅
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\Inform();
        $m->GetOne($id);
    }

}