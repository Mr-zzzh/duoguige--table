<?php

namespace app\mobile\controller;

use think\Cache;
use think\Request;
use think\Session;

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
     * @param name:phone type:string require:1 default:- other:- desc:电话
     * @param name:avatar type:string require:1 default:- other:- desc:头像
     * @param name:password type:string require:1 default:- other:- desc:密码
     * @param name:salt type:string require:1 default:- other:- desc:随机加盐
     * @param name:intro type:string require:1 default:- other:- desc:简介
     * @param name:status type:int require:1 default:- other:- desc:审核状态_0待审_1通过_2不通过
     * @param name:type type:int require:1 default:- other:- desc:用户类型_1,普通用户,2技术大师,3物业公司
     * @param name:token type:string require:1 default:- other:- desc:用户标识
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

}