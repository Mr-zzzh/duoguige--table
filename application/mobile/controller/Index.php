<?php

namespace app\mobile\controller;

use think\Cache;

/**
 * @title 首页及登录
 * @group MOBILE
 */
class Index extends Common {
    /**
     * @title 员工登录
     * @url /login
     * @method post
     * @param name:phone type:string require:0 default:- other:- desc:手机号
     * @param name:password type:string require:0 default:- other:- desc:密码(明文)
     * @return info:用户信息@!
     * @info id:id name:用户姓名 phone:用户手机号 avatar:用户头像 intro:用户简介 status:审核状态_0待审_1通过_2不通过(type为2和3时判断) type:用户类型_1普通用户_2技术大师_3物业公司 identity:身份 token:token createtime:创建时间 normal:是否启用_1启用_2禁用
     * @author 开发者
     */
    public function login() {
        $m   = new \app\mobile\model\User();
        $res = $m->login();
        if ($res === false) {
            show_json(0, $m->getError());
        }
        if ($res['group'] == 1) {
            send_medal(5, 0, 0, $res['id']);
        }
        show_json(1, array('info' => $res));
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

    /**
     * @title 首页
     * @url /home
     * @method GET|POST
     * @return banner:轮播图@
     * @inflist id:id url:图片地址 jumpurl:图片跳转地址
     * @return news:新闻@
     * @cllist id:id title:新闻标题
     * @author 开发者
     */
    public function home() {
        $banner = db('banner')->field('id,url,jumpurl')
            ->where(array('type' => 1, 'status' => 1))->order('sort asc,createtime desc')->select();
        $new    = db('news')->field('id,title')
            ->where('status', 1)->order('view_number desc')->limit(1)->find();
        show_json(1, array('banner' => $banner, 'news' => $new));
    }

    /**
     * @title 首页城市列表
     * @url /city
     * @method GET|POST
     * @return data:城市列表@
     * @inflist id:id name:城市名 code:城市编码
     * @author 开发者
     */
    public function city() {
        $list = Cache::get('city_list');
        if (empty($list)) {
            $list = db('area')->field('id,name,code')
                ->where(array('level' => 2))->select();
            Cache::set('city_list', $list);
        }
        show_json(1, $list);
    }

}
