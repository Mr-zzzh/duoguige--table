<?php

namespace app\mobile\controller;

use think\Cache;
use think\Request;

/**
 * @title 用户管理
 * @group MOBILE
 */
class User extends Common {

    /**
     * @title 验证码发送
     * @url /user/code
     * @method post
     * @param name:phone type:string require:1 default:- other:- desc:电话
     * @author 开发者(暂定60秒有效时间)
     */
    public function code() {
        $phone = \request()->param('phone');
        $key   = 'yunti_code_' . $phone;
        if (Cache::get($key)) {
            show_json(0, '请勿频繁发送验证码');
        }
        $code = intval(random(6, true));
        //$m     = sendCode(13774024983, 12345, '455644');
        //if ($m['status']==1) {
        Cache::set($key, $code, 60);
        //}else{

        //}
        show_json(1, $code);
    }

    /**
     * @title 注册
     * @url /register
     * @method post
     * @param name:phone type:string require:1 default:- other:- desc:电话
     * @param name:code type:string require:1 default:- other:- desc:验证码(暂未使用)
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @author 开发者
     */
    public function register() {
        $pareams = request()->post();
        /* $key     = 'yunti_code_' . trim($pareams['phone']);
         $code    = Cache::get($key);
         if (empty($code)) {
             show_json(0, '验证码无效');
         }
         if ($code != intval($pareams['code'])) {
             show_json(0, '验证码错误');
         }*/
        $m = new \app\mobile\model\User();
        $m->Register($pareams);
    }

    /**
     * @title 编辑
     * @url /user/:id
     * @method put
     * @param name:name type:string require:1 default:- other:- desc:姓名
     * @param name:avatar type:string require:1 default:- other:- desc:头像
     * @param name:intro type:string require:1 default:- other:- desc:简介
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\User();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /admin/user/:id
     * @method get
     * @return id:id
     * @return name:姓名
     * @return phone:电话
     * @return avatar:头像
     * @return intro:简介
     * @return status:审核状态_0待审_1通过_2不通过
     * @return status_text:状态文本
     * @return type:用户类型_1普通用户_2技术大师_3物业公司
     * @return type_text:类型文本
     * @return normal:是否启用_1启用_2禁用
     * @return normal_text:是否启用
     * @return createtime:创建时间
     * @return check:认证信息数组(check1或者check2)
     * @return check1:认证信息@(技术大师)
     * @return check2:认证信息@(物业公司)
     * @check1 id:id uid:用户id name:真实姓名 sex:性别1男2女 idcardno:身份证号码 company_name:公司名称 license_number:公司营业执照号码 company_image:公司营业执照照片 prove_image:在职证明图片 technician_image:技师证件 dimission:离职证明图 createtime:创建时间
     * @check2 id:id uid:用户id company_name:公司名称 phone:联系电话 name:法人姓名 area:公司地址省市区 address:公司详细地址 number:电梯数量 brand:电梯品牌 image:营业执照 createtime:创建时间
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\mobile\model\User();
        $m->GetOne($id);
    }

    /**
     * @title 添加
     * @url /technician
     * @method post
     * @param name:name type:string require:1 default:- other:- desc:真实姓名
     * @param name:sex type:int require:1 default:- other:- desc:性别1男2女
     * @param name:idcardno type:string require:1 default:- other:- desc:身份证号码
     * @param name:company_name type:string require:1 default:- other:- desc:公司名称
     * @param name:license_number type:string require:1 default:- other:- desc:公司营业执照号码
     * @param name:company_image type:string require:1 default:- other:- desc:公司营业执照照片
     * @param name:prove_image type:string require:1 default:- other:- desc:在职证明图片
     * @param name:technician_image type:string require:1 default:- other:- desc:技师证件
     * @author 开发者
     */
    public function technician() {
        $m = new \app\mobile\model\User();
        $m->Technician(request()->post());
    }

    /**
     * @title 我的收藏
     * @url /my_collect
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id name:资料标题 size:大小 view:浏览量 download:下载量
     * @author 开发者
     */
    public function my_collect() {
        $m = new \app\mobile\model\User();
        $m->MyCollect(request()->get());
    }

    /**
     * @title 我的获赞
     * @url /my_like
     * @method get
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data id:id nid:新闻id content:留言内容 like_number:获赞数 createtime:创建时间 day:日期(2019-5-31) time:时间(17:53)
     * @author 开发者
     */
    public function my_like() {
        $m = new \app\mobile\model\User();
        $m->MyLike(request()->get());
    }
}