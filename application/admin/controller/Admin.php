<?php

namespace app\admin\controller;

use think\Request;

/**
 * @title 后台用户管理
 * @group ADMIN
 */
class Admin extends Common {

    /**
     * @title 后台首页
     * @url /admin/index
     * @method get
     * @return data:列表@
     * @data money:今日付款金额 number:今日订单数 pay_number:今日已付款订单数 member:今日新增会员数 trend:近7日交易走势(https://www.echartsjs.com/examples/editor.html?c=line-stack)
     * @author 开发者
     */
    public function admin() {
        $m = new \app\admin\model\Admin();
        $m->Home();
    }

    /**
     * @title 后台管理员列表
     * @url /admin/admin
     * @method get
     * @param name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)
     * @param name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)
     * @param name:status type:int require:0 default:- other:- desc:状态：0-禁用，1-启用
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:名称(账号) status:状态0-禁用1-启用 phone:手机号 avatar:用户头像 createtime:创建时间 status_text:状态文本
     * @author 开发者
     */
    public function index() {
        $m = new \app\admin\model\Admin();
        $m->GetAll(request()->get());
    }

    /**
     * @title 添加
     * @url /admin/admin
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:名称(账号)
     * @param name:phone type:string require:1 default:- other:- desc:手机号
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:avatar type:string require:0 default:- other:- desc:账号头像(不传默认)
     * @param name:status type:int require:1 default:- other:- desc:状态：0-禁用，1-启用
     * @author 开发者
     */
    public function save() {
        $m = new \app\admin\model\Admin();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /admin/admin/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Admin();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /admin/admin/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:名称(账号)
     * @param name:phone type:string require:1 default:- other:- desc:手机号
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:avatar type:string require:0 default:- other:- desc:账号头像(不传默认)
     * @param name:status type:int require:1 default:- other:- desc:状态：0-禁用，1-启用
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Admin();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 文件上传
     * @url /upload
     * @method post
     * @param name:fileid type:string require:0 default:'media' desc:表单name值
     * @param name:media type:文件 require:0 default:'' desc:上传的文件
     * @return url:文件url
     * @author 开发者
     */
    public function upload() {
        upload(input('fileid', 'media'));
    }

    public function ue_upload() {
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
        header('Content-Type: text/html; charset=utf-8');
        $contents = file_get_contents(APP_PATH . 'admin/config.json');
        $CONFIG   = json_decode(preg_replace('/\/\*[\s\S]+?\*\//', '', $contents), true);
        $action   = \request()->param('action');
        switch ($action) {
            case 'config':
                $result = json_encode($CONFIG);
                break;
            case 'uploadimage':
                $result = upload($CONFIG['imageFieldName'], true);
                break;
            case 'uploadscrawl':
                $result = json_encode(array('state' => '功能未开启'));
                break;
            case 'uploadvideo':
                $result = upload($CONFIG['videoFieldName'], true);
                break;
            case 'uploadfile':
                $result = upload($CONFIG['fileFieldName'], true);
                break;
            case 'listimage':
            case 'listfile':
                $result = json_encode(array('state' => '功能未开启', 'list' => array(), 'start' => 0, 'total' => 0));
                break;
            case 'catchimage':
                $result = json_encode(array('state' => '功能未开启', 'list' => array()));
                break;
            default:
                $result = json_encode(array('state' => '请求地址出错'));
                break;
        }
        if (isset($_GET['callback'])) {
            if (preg_match('/^[\w_]+$/', $_GET['callback'])) {
                echo htmlspecialchars($_GET['callback']) . '(' . $result . ')';
            } else {
                echo json_encode(array('state' => 'callback参数不合法'));
            }
        } else {
            echo $result;
        }
        exit();
    }

    /**
     * @title 管理登录
     * @url /admin/login
     * @method post
     * @param name:phone type:string require:0 default:- other:- desc:手机号
     * @param name:password type:string require:0 default:- other:- desc:密码(明文)
     * @return info:用户信息@!
     * @info id:id name:用户名 phone:手机号 token:token status:状态_0禁用_1启用 createtime:创建时间
     * @author 开发者
     */
    public function login() {
        $m   = new \app\admin\model\Admin();
        $res = $m->login();
        if ($res === false) {
            show_json(0, $m->getError());
        }
        show_json(1, array('info' => $res));
    }

    /**
     * @title 管理账号自动注册
     * @url /admin/register
     * @method get
     * @author 开发者
     */
    public function register() {
        $m   = new \app\admin\model\Admin();
        $res = $m->register();
        if ($res === false) {
            show_json(0, $m->getError());
        }
        show_json(1, '操作成功!');
    }

    /**
     * @title 读取
     * @url /admin/admin/:id
     * @method get
     * @return id:id
     * @return name:名称(账号)
     * @return phone:手机号
     * @return status:状态：0-禁用，1-启用
     * @return token:用户token
     * @return createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\admin\model\Admin();
        $m->GetOne($id);
    }
}