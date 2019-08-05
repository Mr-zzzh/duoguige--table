<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 发货提醒
 * @group ADMIN
 */
class Remind extends Common {

    /**
     * @title 未读发货提醒数量获取接口
     * @url /admin/remind/unreadnum
     * @method get|post
     * @return total:未读消息总数
     * @author 开发者
     */
    public function unreadnum() {
        $m     = new \app\admin\model\Remind();
        $total = $m->unreadnum();
        if ($total === false) {
            show_json(0, $m->getError());
        }
        show_json(1, array('total' => $total));
    }

    /**
     * @title 发货提醒列表
     * @url /admin/remind
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @param name:status type:int require:0 default:1 desc:状态0_未阅_已阅
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id uname:购买人姓名 uphone:购买人电话 name:商品名 ordersn:订单号 createtime:创建时间 status:状态0未阅1已阅
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Remind();
        $m->GetAll(request()->get());
    }

    /**
     * @title 删除
     * @url /admin/remind/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Remind();
        $m->DelOne($id);
    }

    /**
     * @title 读取
     * @url /admin/remind/:id
     * @method get
     * @return id:id
     * @return name:商品名
     * @return uname:购买用户姓名
     * @return uphone:购买人电话
     * @return ordersn:订单号
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Remind();
        $m->GetOne($id);
    }

}