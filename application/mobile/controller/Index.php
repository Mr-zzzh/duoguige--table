<?php

namespace app\mobile\controller;

/**
 * @title Index
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
     * @return inflist:待办事项@
     * @inflist id:id content:事项内容 nexttime:待办时间
     * @return cllist:遗忘客户@
     * @cllist id:id name:客户名称 lastfllowtime:最后跟进时间 days:天数
     * @return rlist:待查阅日报@
     * @rllist id:id name:姓名 type:简报类型1日报|2周报|3月报 createtime:提交时间
     * @author 开发者
     */
    public function home() {
        global $member;
        //今日待办
        $tstarttime = StEntime('d', 's');
        $tendtime   = StEntime('d', 'e');
        $infModel   = new \app\mobile\model\Inform();
        $inflist    = $infModel->field('id,content,nexttime')->where(array('nexttime' => ['BETWEEN', $tstarttime . ',' . $tendtime]))->limit(2)->select()->toArray();
        if (!empty($inflist)) {
            foreach ($inflist as &$v) {
                $v['nexttime'] = date('H:i', $v['nexttime']);
            }
            unset($v);
        }
        //被遗忘客户
        $clientinfo    = get_set('business')['client'];
        $writtenstatus = intval($clientinfo['written']);
//        $forget = time()-($clientinfo['forget']?:1)*86400;
        $forget  = time() - 43200;
        $clModel = new \app\mobile\model\Clientele();
        $cllist  = $clModel->where(array('mid' => $member['id'], 'lastfllowtime' => ['<', $forget], 'newop2' => ['<>', $writtenstatus]))->field('id,name,lastfllowtime')->limit(2)->select()->toArray();
        if (!empty($cllist)) {
            foreach ($cllist as &$v) {
                $timess             = time() - $v['lastfllowtime'];
                $v['days']          = floor($timess / 86400);
                $v['lastfllowtime'] = date('Y-m-d H:i:s', $v['lastfllowtime']);
            }
            unset($v);
        }
        //待查阅
        if ($member['group'] == 0 || $member['type'] == 2) {
            $where          = array();
            $where['a.cid'] = $member['cid'];
            $where['b.mid'] = null;
            if ($member['group'] == 1) {
                $where['a.did'] = $member['did'];
            }
            $rlist = db('brief_report')->alias('a')->field('a.id,c.name,a.type,a.createtime')
                ->join('coa_brief_report_read b', 'a.id=b.rid', 'left')
                ->join('coa_member c', 'a.mid=c.id', 'left')
                ->where($where)
                ->order('a.createtime desc')
                ->limit(2)
                ->select();
            if (!empty($rlist)) {
                foreach ($rlist as &$item) {
                    $item['createtime'] = date("Y年m月d日", $item['createtime']);
                }
                unset($item);
            }
        } else {
            $rlist = array();
        }
        show_json(1, array('inflist' => $inflist, 'cllist' => $cllist, 'rlist' => $rlist));
    }
}
