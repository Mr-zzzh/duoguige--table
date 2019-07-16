<?php

namespace app\mobile\controller;

use http\Client;
use think\Cache;

/**
 * @title 首页及登录
 * @group MOBILE
 */
class Index extends Common {
    /**
     * @title 员工登录(密码登录)
     * @url /login
     * @method post
     * @param name:phone type:string require:1 default:- other:- desc:手机号
     * @param name:password type:string require:1 default:- other:- desc:密码(明文)
     * @return data:用户信息@!
     * @data id:id name:用户姓名 phone:用户手机号 avatar:用户头像 intro:用户简介 status:审核状态_0待审_1通过_2不通过(type为2和3时判断) type:用户类型_1普通用户_2技术大师_3物业公司 identity:身份 company:公司名(技术大师和物业身份有) token:token createtime:创建时间 normal:是否启用_1启用_2禁用
     * @author 开发者
     */
    public function login() {
        $m   = new \app\mobile\model\User();
        $res = $m->login();
        if ($res === false) {
            show_json(0, $m->getError());
        }
        show_json(1, $res);
    }

    /**
     * @title 员工登录(验证码登录)
     * @url /login_code
     * @method post
     * @param name:phone type:string require:1 default:- other:- desc:手机号
     * @param name:code type:string require:1 default:- other:- desc:验证码
     * @return data:用户信息@!
     * @data id:id name:用户姓名 phone:用户手机号 avatar:用户头像 intro:用户简介 status:审核状态_0待审_1通过_2不通过(type为2和3时判断) type:用户类型_1普通用户_2技术大师_3物业公司 identity:身份 company:公司名(技术大师和物业身份有) token:token createtime:创建时间 normal:是否启用_1启用_2禁用
     * @author 开发者
     */
    public function login_code() {
        $pareams = request()->param();
        if (empty($pareams['phone'])) {
            show_json(0, '手机号不能为空');
        }
        /*if (empty($pareams['code'])) {
            show_json(0, '验证码不能为空');
        }*/
        /* $key     = 'yunti_code_' . trim($pareams['phone']);
         $code    = Cache::get($key);
         if (empty($code)) {
             show_json(0, '验证码无效');
         }
         if ($code != intval($pareams['code'])) {
             show_json(0, '验证码错误');
         }*/
        $m = new \app\mobile\model\User();
        $m->LoginCode(trim($pareams['phone']));
    }

    /**
     * @title 文件上传
     * @url /mobile/upload
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
     * @title 保险页面
     * @url /insurance
     * @method GET|POST
     * @return data:保险@
     * @inflist id:id url:图片地址 jumpurl:图片跳转地址
     * @author 开发者
     */
    public function insurance() {
        $banner = db('banner')->field('id,url,jumpurl')
            ->where(array('type' => 2, 'status' => 1))->order('sort asc,createtime desc')->select();
        show_json(1, array('data' => $banner));
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
        show_json(1, array('data' => $list));
    }

    /**
     * @title 搜索
     * @url /search
     * @method get
     * @param name:keyword type:string require:0 default:- other:- desc:关键字检索
     * @param name:type type:int require:1 default:- other:- desc:类型_1商城_2新闻_3问答_4_招聘_5求职(不传默认商城)
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id title:标题 type:新闻类型(1图文2视频)
     * @author 开发者
     */
    public function search() {
        global $member;
        $params = request()->param();
        $map    = array();
        if (!empty($params['keyword'])) {
            if (!empty($member)) {
                $history            = array();
                $history['uid']     = $member['id'];
                $history['type']    = 1;
                $history['content'] = trim($params['keyword']);
                if (!db('search_history')->where($history)->value('id')) {
                    $history['createtime'] = time();
                    db('search_history')->insert($history);
                }
            }
            $type = intval($params['type']);
            //商城
            if ($type == 1 || empty($type)) {
                $map['name|specification|model|manufacturers|phone|label|intro|area|province|color|address'] = array('LIKE', '%' . trim($params['keyword']) . '%');
                $list                                                                                        = db('goods')
                    ->field('id,name as title')
                    ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
                show_json(1, $list);
            } elseif ($type == 2) {
                $map['title|content'] = array('LIKE', '%' . trim($params['keyword']) . '%');
                $map['status']        = 1;
                $list                 = db('news')->where($map)
                    ->field('id,title,type')
                    ->order('sort asc,createtime desc')->paginate($params['limit'])->toArray();
                show_json(1, $list);
            } elseif ($type == 3) {
                $map['title'] = array('LIKE', '%' . trim($params['keyword']) . '%');
                $map['type']  = 1;
                $list         = db('question')->field('id,title')
                    ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
                show_json(1, $list);
            } elseif ($type == 4) {
                $map['post|description|duty|name|phone'] = array('LIKE', '%' . trim($params['keyword']) . '%');
                $map['status']                           = 1;
                $list                                    = db('invite')
                    ->field('id,post as title')
                    ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
                show_json(1, $list);
            } elseif ($type == 4) {
                $map['post|intro|address|name'] = array('LIKE', '%' . trim($params['keyword']) . '%');
                $list                           = db('job_wanted')
                    ->field('id,post as title')
                    ->where($map)->order('createtime desc')->paginate($params['limit'])->toArray();
                show_json(1, $list);
            }
        }
        show_json(1);
    }

    /**
     * @title 搜索历史列表
     * @url /history
     * @method get
     * @param name:type type:int require:1 default:- other:- desc:类型_1首页搜索_2故障库搜索(不传默认首页)
     * @return data:列表@
     * @data id:id content:内容
     * @author 开发者
     */
    public function history() {
        global $member;
        $type = intval(request()->get('type'));
        $map  = array();
        if (empty($type) || $type == 1) {
            $map['type'] = 1;
        } else {
            $map['type'] = 2;
        }
        $map['uid'] = $member['id'];
        $list       = db('search_history')->where($map)
            ->field('id,content')->order('createtime desc')->select();
        show_json(1, array('data' => $list));
    }

    /**
     * @title 搜索历史清除
     * @url /history_del
     * @method post
     * @param name:type type:int require:1 default:- other:- desc:类型_1首页搜索_2故障库搜索(不传默认首页)
     * @author 开发者
     */
    public function history_del() {
        global $member;
        $type = intval(request()->post('type'));
        $map  = array();
        if (empty($type) || $type == 1) {
            $map['type'] = 1;
        } else {
            $map['type'] = 2;
        }
        $map['uid'] = $member['id'];
        if (db('search_history')->where($map)->delete()) {
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    /**
     * @title 分享
     * @url /share
     * @method get
     * @return data:列表@
     * @data id:id title:分享标题 icon:分享图标 intro:分享描述 share_link:分享链接
     * @author 开发者
     */
    public function share() {
        $list = db('share')->order('createtime desc')->limit(1)->find();
        show_json(1, array('data' => $list));
    }

    /**
     * @title 翻译
     * @url /translate
     * @method post
     * @param name:content type:string require:1 default:- other:- desc:翻译内容
     * @param name:type type:int require:1 default:- other:- desc:类型_1中英_2英中
     * @author 开发者
     */
    public function translate() {
        $params  = request()->post();
        $content = trim($params['content']);
        if (empty($params['type'])) {
            $type = 1;
        } else {
            $type = intval($params['type']);
        }
        $url     = "http://fanyi.sogou.com:80/reventondc/api/sogouTranslate";
        $pid     = "933674c0031f9b9d2d5a29c32add87ec";
        $salt    = random(13, true);
        $key     = "1e121b06b6108f1651f03c9dc45f4e5f";
        $sign    = md5($pid . $content . $salt . trim($key));
        $headers = [
            'content-type' => "application/x-www-form-urlencoded",
            'accept'       => "application/json"
        ];
        if ($type == 1) {
            $payload = "from=zh-CHS&to=en&pid=" . $pid . "&q=" . $content . "&sign=" . $sign . "&salt=" . $salt;
        } else {
            $payload = "from=en&to=zh-CHS&pid=" . $pid . "&q=" . $content . "&sign=" . $sign . "&salt=" . $salt;
        }
        $result = curl($url, 'POST', $payload, $headers);
        show_json(1, $result);
    }

}
