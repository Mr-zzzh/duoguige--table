<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

if (!function_exists('random')) {

    function random($length, $numeric = FALSE) {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

}
if (!function_exists('show_json')) {

    function show_json($status = 1, $return = '') {
        $ret = array('status' => $status, 'message' => '', 'data' => null);
        if (is_array($return)) {
            if (isset($return['message'])) {
                $ret['message'] = $return['message'];
                unset($return['message']);
            }
            $ret['data'] = $return;
        } else {
            $ret['message'] = $return;
        }
        if (empty($ret['message'])) {
            $ret['message'] = $status > 0 ? '操作成功' : '操作失败';
        }
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode($ret));
    }

}
if (!function_exists('is_mobile')) {

    function is_mobile() {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return true;
        }
        return false;
    }

}

//后台用户登录
if (!function_exists('admin_login')) {
    function admin_login() {
        $token = \think\Request::instance()->header('token');
        if (empty($token)) {
            $params = \think\Request::instance()->param();
            $token  = $params['token'];
        }
        if (empty($token)) {
            return false;
        }
        $m   = new \app\admin\model\Admin();
        $res = $m->login($token);
        return $res;
    }
}
//手机端用户登录
if (!function_exists('mobile_login')) {
    function mobile_login() {
        $token = \think\Request::instance()->header('token');
        if (empty($token)) {
            $params = \think\Request::instance()->param();
            $token  = $params['token'];
        }
        if (empty($token)) {
            return false;
        }
        $m   = new \app\mobile\model\User();
        $res = $m->login($token);
        return $res;
    }
}

//公共方法验证
if (!function_exists('is_comc')) {
    function is_comc() {
        $module     = \think\Request::instance()->module();
        $controller = \think\Request::instance()->controller();
        $action     = \think\Request::instance()->action();
        $path       = strtolower($module . '/' . $controller . '/' . $action);
        $comc       = login_comc();
        if (in_array($path, $comc)) {
            return true;
        } else {
            return false;
        }
    }
}

//省市区转树
if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
        $tree = array();
        if (is_array($list)) {
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[$data[$pk]] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent                     = &$refer[$parentId];
                        $parent[$child][$data[$pk]] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

//公共方法(无需登录判断)
if (!function_exists('login_comc')) {
    function login_comc() {
        $comc = array('admin/admin/login', 'admin/admin/ue_upload', 'admin/admin/register', 'mobile/index/login', 'mobile/index/login_code', 'mobile/index/insurance', 'mobile/index/home', 'mobile/index/search', 'mobile/area/index', 'mobile/index/city', 'mobile/index/translate', 'mobile/user/code', 'mobile/user/register', 'mobile/goods/goodscate', 'mobile/goods/index', 'mobile/maintenance/city', 'mobile/goods/read', 'mobile/brand/index', 'mobile/brand/branddatum', 'mobile/brand/read', 'mobile/invite/salary', 'mobile/invite/experience', 'mobile/fault/transition', 'mobile/fault/index', 'mobile/fault/read', 'mobile/question/index', 'mobile/question/read', 'mobile/question/answer', 'mobile/news/index', 'mobile/technician/index', 'mobile/technician/read', 'mobile/technician/question');
        return $comc;
    }
}
//公共方法(无需权限限制)
if (!function_exists('comc')) {
    function comc() {
        $comc = array('admin/index/login', 'admin/index/tokenlogin', 'admin/index/check_login_out', 'admin/member/register', 'admin/index/loweraddress', 'admin/index/citycode', 'admin/index/getcity', 'mobile/index/login', 'admin/index/index', 'admin/index/markhometd', 'admin/index/markhome', 'admin/index/myclientele', 'admin/inform/unreadnum', 'admin/inform/unreadlist', 'admin/inform/index', 'admin/inform/informread', 'admin/index/member_info', 'admin/index/member_edit', 'admin/index/buswelcome', 'admin/index/clout', 'admin/index/lout', 'admin/index/tokenlogin', 'admin/index/upload', 'admin/index/ue_upload', 'upload', 'mobile/upload', 'admin/index/bushometop', 'admin/index/bushomemiddle', 'admin/index/busbottom', 'admin/position/getbydid', 'admin/member/getnamelist', 'admin/member/searchman', 'admin/department/getname', 'admin/index/navtion', 'admin/briefreport/calendar', 'admin/projectdemandcate/clist', 'admin/industry/isearch', 'admin/options/getsearch', 'admin/projectdemand/batch', 'admin/briefreport/report_calendar');
        return $comc;
    }
}
//权限获取
if (!function_exists('getController')) {

    function getController($module) {
        if (empty($module)) {
            return array();
        }
        $module_path = APP_PATH . '/' . $module . '/controller/'; //控制器路径
        if (!is_dir($module_path)) {
            return array();
        }
        $module_path .= '/*.php';
        $ary_files   = glob($module_path);
        $files       = array();
        foreach ($ary_files as $file) {
            if (is_dir($file) || (basename($file, '.php') == 'Common')) {
                continue;
            }
            $files[] = basename($file, '.php');
        }
        return $files;
    }

}
if (!function_exists('getAction')) {

    function getAction($controller) {
        if (empty($controller)) {
            return array();
        }
        $customer_functions  = array();
        $con                 = controller($controller);
        $functions           = get_class_methods($con);  //排除部分方法
        $inherents_functions = array('_initialize', '__construct', 'getActionName', 'isAjax', 'display', 'show', 'fetch', 'buildHtml', 'assign', '__set', 'get', '__get', '__isset', '__call', 'error', 'success', 'ajaxReturn', 'redirect', '__destruct', '_empty', 'miss', 'check_rule', 'check_login', 'post');
        foreach ($functions as $func) {
            if (!in_array($func, $inherents_functions)) {
                $customer_functions[] = $func;
            }
        }
        return $customer_functions;
    }

}
if (!function_exists('get_action_desc')) {

    function get_action_desc($module, $controller, $action, $sign = '@title', $empty = '-') {
        $func = new ReflectionMethod('\\app\\' . $module . '\\controller\\' . $controller, $action);
        $tmp  = array();
        preg_match_all('/' . $sign . '(.*?)\n/', $func->getDocComment(), $tmp);
        if (isset($tmp[1][0]) && (!empty($tmp[1][0]))) {
            if (count($tmp[1]) > 1) {
                return $tmp[1];
            }
            return trim($tmp[1][0]);
        } else {
            return $empty;
        }
    }

}
if (!function_exists('get_controller_desc')) {

    function get_controller_desc($module, $controller, $sign = '@title') {
        $func = new ReflectionClass('\\app\\' . $module . '\\controller\\' . $controller);
        $tmp  = array();
        preg_match_all('/' . $sign . '(.*?)\n/', $func->getDocComment(), $tmp);
        if (isset($tmp[1][0]) && (!empty($tmp[1][0]))) {
            return trim($tmp[1][0]);
        } else {
            return '-';
        }
    }

}
if (!function_exists('get_authority')) {
    function get_authority() {
        cookie('coa__nocommon', null);
        cookie('coa__nocommon', 1, array('httponly' => TRUE));
        $data    = array();
        $modules = array('admin' => array('text' => 'OA管理系统'));
        foreach ($modules as $module => $v1) {
            $all_controller = getController($module);
            foreach ($all_controller as $controller) {
                if (in_array($controller, array('BriefReportDetails', 'BriefReportDemand', 'Inform', 'BriefReportComment', 'Task'))) {
                    continue;
                }
                $all_action                = getAction($module . '/' . $controller);
                $data[$controller]['name'] = get_controller_desc($module, $controller);
                foreach ($all_action as $k => $action) {
                    if (in_array(strtolower($module . '/' . $controller . '/' . $action), comc())) {
                        continue;
                    }
                    $data[$controller]['rule'][$k]['key']   = strtolower($module . '/' . $controller . '/' . $action);
                    $data[$controller]['rule'][$k]['value'] = get_action_desc($module, $controller, $action);

//                    $data[$controller]['rule'][strtolower($module.'/'.$controller.'/'.$action)] = get_action_desc($module, $controller, $action);
                }
            }
        }
        cookie('coa__nocommon', null);
        return $data;
    }
}

//手机号验证
if (!function_exists('is_mobilenumber')) {
    function is_mobilenumber($mobile) {
        if (empty($mobile)) {
            return FALSE;
        }
        if (!preg_match("/^1[3456789]\d{9}$/", $mobile)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

//操作日志添加
if (!function_exists('log')) {
    function logs($remark = '', $mid = '', $content = '') {
        $logModel = new \app\admin\model\OperationLog();
        $logModel->saveOperationLog($remark, $mid, $content);
    }
}
//json转换
if (!function_exists('to_json')) {
    function to_json($content = '') {
        if (is_string($content)) {
            if ($content == json_encode(json_decode($content, true))) {
                return $content;
            } else {
                return json_encode($content);
            }
        } else {
            return json_encode($content);
        }

    }
}

//获取对应下级省市区
if (!function_exists('lower_address')) {
    function lower_address($code = '') {
        $address = new \app\admin\model\Area();
        $list    = $address->getlist($code);
        if ($list === false) {
            return array('status' => 0, 'message' => '获取失败');
        }
        return array('status' => 1, 'list' => $list);
    }
}
//按字母排序获取城市
if (!function_exists('get_city')) {
    function get_city() {
        $address = new \app\admin\model\Area();
        $list    = $address->getcity();
        if ($list === false) {
            return array('status' => 0, 'message' => '获取失败');
        }
        return array('status' => 1, 'list' => $list);
    }
}
//根据编码获取城市名
if (!function_exists('city_name')) {
    function city_name($code = '') {
        $address = new \app\admin\model\Area();
        $name    = $address->cityname($code);
        return $name;
    }
}
//根据城市名获取城市编码
if (!function_exists('city_code')) {
    function city_code($level = 1, $name = '') {
        $address = new \app\admin\model\Area();
        $name    = $address->citycode($level, $name);
        return $name;
    }
}

if (!function_exists('save_media')) {

    function save_media($path) {
        $accessKeyId     = 'LTAIQYwnAGevqIoq';
        $accessKeySecret = 'i91lpHReLdG3dMC9kLYT8L6RCusdmo';
        $endpoint        = 'oss-cn-hangzhou.aliyuncs.com';
        $bucket          = 'ccia-oa';
        $url             = '';
        try {
            require_once __DIR__ . '/../extend/aliyunoss/autoload.php';
            $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $object    = ltrim($path, '/');
            $file      = ROOT_PATH . 'public_html' . $path;
            $result    = $ossClient->uploadFile($bucket, $object, $file);
            if ($result['oss-request-url']) {
                $url = $result['oss-request-url'];
            }
        } catch (\OSS\Core\OssException $e) {
            trace($e->getMessage(), 'OssError');
        }
        return $url;
    }

}
if (!function_exists('upload')) {

    function upload($fileId, $ue = false) {
        $file = request()->file($fileId);
        if (empty($file)) {
            if ($ue) {
                return json_encode(array('state' => '没有找到上传文件'));
            } else {
                show_json(0, '没有找到上传文件');
            }
        }
        $ext = strtolower(pathinfo($file->getInfo('name'), PATHINFO_EXTENSION));
        if (in_array($ext, array('php'))) {
            if ($ue) {
                return json_encode(array('state' => '文件类型被禁止上传'));
            } else {
                show_json(0, '文件类型被禁止上传');
            }
        }
        if (empty($ext)) {
            $ext = 'none';
        }
        $thumbext = array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'ico', 'psd');
        $audioext = array('mp3', 'wma', 'wav', 'amr');
        $videoext = array('rm', 'rmvb', 'wmv', 'avi', 'mpg', 'mpeg', 'mp4', 'mov', 'flv', 'swf', 'mkv', 'ogg', 'ogv', 'webm', 'mid');
        $docext   = array('txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pps', 'pdf', 'chm', 'md', 'json', 'sql');
        $otherext = array('rar', 'zip', '7z', 'tar', 'gz', 'bz2', 'cab', 'iso', 'tar.gz', 'mmap', 'xmind', 'md', 'xml');
        if (!in_array($ext, array_merge($thumbext, $audioext, $videoext, $docext, $otherext))) {
            if ($ue) {
                return json_encode(array('state' => '文件类型被禁止上传'));
            } else {
                show_json(0, '文件类型被禁止上传');
            }
        }
        $info = $file->validate(array('size' => 30 * 1024 * 1024))->rule('md5')->move(ROOT_PATH . 'public_html/uploads/' . $ext);
        if ($info) {
            $path = str_replace(DS, '/', '/uploads/' . $ext . '/' . $info->getSaveName());
            $url  = request()->domain() . $path;
            /* if ($info->getInfo('size') <= 5 * 1024 * 1024) {
                 $url = save_media($path);
             }*/
            $size = $info->getInfo('size');
            \think\Cache::set($url, $size, 300);
            if ($ue) {
                return json_encode(array('state' => 'SUCCESS', 'url' => $url));
            } else {
                show_json(1, array('message' => '上传成功', 'url' => $url));
            }
        } else {
            if ($ue) {
                return json_encode(array('state' => $file->getError()));
            } else {
                show_json(0, $file->getError());
            }
        }
    }

}

//配置信息添加
if (!function_exists('save_set')) {
    function save_set($data, $module = 'system') {
        \think\Cache::rm($module . '_system');
        $set     = new \app\admin\model\System();
        $setdata = $set->where(array('module' => $module))->field('id,sets')->find();
        if (empty($setdata)) {
            $set->save(array('sets' => serialize($data), 'module' => $module));
        } else {
            $sets = unserialize($setdata['sets']);
            $sets = is_array($sets) ? $sets : array();
            foreach ($data as $key => $value) {
                $sets[$key] = $value;
            }
            $set->where(array('id' => $setdata['id']))->update(array('sets' => serialize($sets), 'module' => $module));
        }
        return get_set($module);
    }
}
//配置信息获取
if (!function_exists('get_set')) {
    function get_set($module = 'system') {
        $set  = new \app\admin\model\System();
        $sets = \think\Cache::get($module . '_system');
        if (empty($sets)) {
            $set = $set->where(array('module' => $module))->value('sets');
            if (!empty($set)) {
                $sets = unserialize($set);
                \think\Cache::set($module . '_system', $sets);
                return $sets;
            } else {
                return array();
            }
        } else {
            return $sets;
        }
    }
}

if (!function_exists('numberarr')) {

    function numberarr($number = 0) {
        $total     = max(ceil($number / 4), 1);
        $numberarr = [];
        for ($i = 0; $i < 5; $i++) {
            $numberarr[] = 0 + $total * $i;
        }
        return $numberarr;
    }

}

//计算多长时间以前
if (!function_exists('time_tran')) {
    function time_tran($the_time) {
        $now_time = time();
        $dur      = $now_time - $the_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return '3天前';
                        }
                    }
                }
            }
        }
    }
}

//curl请求
if (!function_exists('curl')) {
    function curl($url, $method = '', $params = array(), $header = array(), $auth = '', $timeout = 10) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (empty($header)) {
            $header[] = 'Content-Type:application/json;charset=utf-8';
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        if (empty($method) || strtolower($method) == 'get') {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        } else {
            $params = is_array($params) ? json_encode($params, 320) : $params;
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }
        if ($auth) {
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
        }
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $res;
    }
}

//频繁操作
if (!function_exists('check_often')) {
    function check_often($key = '', $timeout = 3) {
        if (empty($key)) {
            return false;
        }
        if (\think\Cache::get($key)) {
            return true;
        } else {
            \think\Cache::set($key, true, $timeout);
            return false;
        }
    }
}

if (!function_exists('sendCode')) {
    function sendCode($mobile, $code, $tempId) {
        AlibabaCloud::accessKeyClient(config('app.aliyunsms.access_key_id'), config('app.aliyunsms.access_key_secret'))
            ->regionId('cn-hangzhou')//replace regionId as you need（这个地方是发短信的节点，默认即可，或者换成你想要的）
            ->asGlobalClient();
        $data = [];
        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                //->scheme('https') //https | http（如果域名是https，这里记得开启）
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers'  => $mobile,
                        'SignName'      => config('app.aliyunsms.sign_name'),
                        'TemplateCode'  => $tempId,
                        'TemplateParam' => json_encode(['code' => $code]),
                    ],
                ])->request();
            $res    = $result->toArray();
            if ($res['Code'] == 'OK') {
                $data['status'] = 1;
                $data['info']   = $res['Message'];
            } else {
                $data['status'] = 0;
                $data['info']   = $res['Message'];
            }
            return $data;
        } catch (ClientException $e) {
            $data['status'] = 0;
            $data['info']   = $e->getErrorMessage();
            return $data;
        } catch (ServerException $e) {
            $data['status'] = 0;
            $data['info']   = $e->getErrorMessage();
            return $data;
        }
    }
}