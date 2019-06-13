<?php

error_reporting(0);
ignore_user_abort();
set_time_limit(0);
date_default_timezone_set('PRC');
ini_set('memory_limit', '-1');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('APP_PATH') or define('APP_PATH', dirname(__DIR__) . DS . 'application' . DS);

function get_config() {
    static $config;
    if (empty($config)) {
        $config           = array(
            'run'           => true,
            'create_common' => true,
            'create_other'  => true,
            'tables'        => array(),
            'host'          => '127.0.0.1',//数据库地址
            'database'      => 'yunti',//数据库名
            'username'      => 'root',//数据库账号
            'password'      => '123456',//数据库密码
            'prefix'        => 'yunti_',//数据表表前缀
            'module'        => array(
                //'index' => true,//为true时路由省略模块名
                'admin'  => false,
                'mobile' => true,
            ),//自动生成的模块
            'common'        => 'Common',//公共控制器
        );
        $config['common'] = $config['common'] ? ucfirst($config['common']) : 'Common';
    }
    return $config;
}

function sqlcz($sql) {
    $config = get_config();
    $link   = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);
    if (empty($link)) {
        die('mysql error');
    }
    mysqli_query($link, 'set names utf8');
    $res = mysqli_query($link, $sql);
    if (is_bool($res)) {
        $return = $res;
    } else {
        $return = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $return[] = $row;
        }
        mysqli_free_result($res);
    }
    mysqli_close($link);
    return $return;
}

function strexists($string, $find) {
    return !(strpos($string, $find) === FALSE);
}

function strexists2($string, $array) {
    $string = strtolower($string);
    foreach ($array as $v) {
        if (strexists($string, strtolower($v))) {
            return true;
        }
    }
    return false;
}

function mkdirs($path) {
    if (!is_dir($path)) {
        mkdirs(dirname($path));
        mkdir($path, 0777);
        chmod($path, 0777);
    }
    return is_dir($path);
}

function get_hump($table_name) {
    $config = get_config();
    $temp   = explode('_', str_replace($config['prefix'], '', $table_name));
    foreach ($temp as &$v) {
        $v = ucfirst(strtolower($v));
    }
    unset($v);
    return implode('', $temp);
}

function create_common($module) {
    $config = get_config();
    $path   = APP_PATH . $module . DS . 'controller' . DS;
    mkdirs($path);
    $data = <<<'DATA'
<?php

namespace app\{var_module}\controller;

use think\Controller;

class {var_common} extends Controller {

    public function _initialize() {
        parent::_initialize();
        set_exception_handler(function ($e) {
            show_json(-1, $e->getMessage() ?: '系统出现异常');
        });
        header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,token');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        $module = request()->module();
        $controller = request()->controller();
        $action = request()->action();
        $path = strtolower($module . '/' . $controller . '/' . $action);
        //TODO 权限校验等逻辑处理
    }

    public function miss() {
        show_json(-2, '路由未定义!');
    }

}
DATA;
    $data = str_replace(array('{var_module}', '{var_common}'), array($module, $config['common']), $data);
    file_put_contents($path . $config['common'] . '.php', $data);
    echo $path . $config['common'] . '.php----------create succeed<br/>';
}

function create_common2($module) {
    $config = get_config();
    $path   = APP_PATH . $module . DS . 'model' . DS;
    mkdirs($path);
    $data = <<<'DATA'
<?php

namespace app\{var_module}\model;

use think\Model;

class {var_common} extends Model {

    protected function initialize() {
        parent::initialize();
    }

}
DATA;
    $data = str_replace(array('{var_module}', '{var_common}'), array($module, $config['common']), $data);
    file_put_contents($path . $config['common'] . '.php', $data);
    echo $path . $config['common'] . '.php----------create succeed<br/>';
}

function get_tables() {
    static $tables;
    if (empty($tables)) {
        $config    = get_config();
        $tables    = array();
        $all_table = sqlcz('SHOW TABLES FROM ' . $config['database'] . ' LIKE \'' . $config['prefix'] . '%\'');
        foreach ($all_table as $table) {
            $table_name          = $table[key($table)];
            $tables[$table_name] = array(
                'comment'          => sqlcz('SELECT TABLE_COMMENT FROM information_schema.tables WHERE table_schema=DATABASE() AND TABLE_NAME=\'' . $table_name . '\'')[0]['TABLE_COMMENT'],
                'hump'             => get_hump($table_name),
                'field'            => array(),
                'exist_id'         => false,
                'exist_createtime' => false,
                'exist_updatetime' => false,
                'exist_status'     => false,
                'exist_enabled'    => false,
                'exist_type'       => false,
                'keywords'         => array(),
            );
            if (empty($tables[$table_name]['comment'])) {
                $tables[$table_name]['comment'] = $tables[$table_name]['hump'];
            }
            $fields = sqlcz('SHOW FULL FIELDS FROM `' . $table_name . '`');
            foreach ($fields as $v) {
                $v['Field']   = strtolower($v['Field']);
                $v['Comment'] = str_replace(' ', '_', $v['Comment'] ?: $v['Field']);
                $v['Field'] == 'id' && $tables[$table_name]['exist_id'] = true;
                $v['Field'] == 'createtime' && $tables[$table_name]['exist_createtime'] = true;
                $v['Field'] == 'updatetime' && $tables[$table_name]['exist_updatetime'] = true;
                $v['Field'] == 'status' && $tables[$table_name]['exist_status'] = $v['Comment'];
                $v['Field'] == 'enabled' && $tables[$table_name]['exist_enabled'] = $v['Comment'];
                $v['Field'] == 'type' && $tables[$table_name]['exist_type'] = $v['Comment'];
                if (strexists2($v['Type'], array('TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'INTEGER', 'BIGINT'))) {
                    $v['Type'] = 'int';
                } elseif (strexists2($v['Type'], array('FLOAT', 'DOUBLE', 'DECIMAL'))) {
                    $v['Type'] = 'float';
                } elseif (strexists2($v['Type'], array('DATE', 'TIME', 'YEAR', 'DATETIME', 'TIMESTAMP'))) {
                    $v['Type'] = 'date';
                } elseif (strexists2($v['Type'], array('CHAR', 'VARCHAR', 'TINYBLOB', 'TINYTEXT', 'BLOB', 'TEXT', 'MEDIUMBLOB', 'MEDIUMTEXT', 'LONGBLOB', 'LONGTEXT'))) {
                    $v['Type']                         = 'string';
                    $tables[$table_name]['keywords'][] = $v['Field'];
                } else {
                    $v['Type'] = str_replace(' ', '_', $v['Type']);
                }
                $tables[$table_name]['field'][] = array($v['Field'], $v['Type'], $v['Comment']);
            }
            $tables[$table_name]['keywords'] = implode('|', $tables[$table_name]['keywords']);
            if (empty($tables[$table_name]['exist_id']) || empty($tables[$table_name]['field'])) {
                unset($tables[$table_name]);
            }
        }
    }
    return $tables;
}

function create_other($module) {
    $config = get_config();
    $path   = APP_PATH . $module . DS;
    mkdirs($path);
    $data        = <<<'DATA'
//common.php
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
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
        $ret = array('status' => $status, 'message' => '', 'result' => array());
        if (is_array($return)) {
            if (isset($return['message'])) {
                $ret['message'] = $return['message'];
                unset($return['message']);
            }
            $ret['result'] = $return;
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
if (!function_exists('save_media')) {

    function save_media($path) {
        $accessKeyId = 'LTAIQYwnAGevqIoq';
        $accessKeySecret = 'i91lpHReLdG3dMC9kLYT8L6RCusdmo';
        $endpoint = 'oss-cn-shanghai.aliyuncs.com';
        $bucket = '';//TODO 阿里云oss后台创建
        $url = '';
        try {
            require_once ROOT_PATH . 'extend/aliyunoss/autoload.php';
            $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $object = ltrim($path, '/');
            $file = ROOT_PATH . 'public_html' . $path;
            $result = $ossClient->uploadFile($bucket, $object, $file);
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
        $info = $file->rule('md5')->move(ROOT_PATH . 'public_html/uploads/' . $ext);
        if ($info) {
            $path = str_replace(DS, '/', '/uploads/' . $ext . '/' . $info->getSaveName());
            $url = save_media($path);
            if ($ue) {
                return json_encode(array('state' => 'SUCCESS', 'url' => $url ?: request()->domain() . $path));
            } else {
                show_json(1, array('message' => '上传成功', 'url' => $url ?: request()->domain() . $path));
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

//doc.php
//需要生成文档的类
{var_doc}

//route.php
{var_route}
DATA;
    $controller  = $route = '';
    $controllers = get_controller();
    foreach ($controllers as $v) {
        if (strtolower($v) == strtolower($config['common'])) {
            continue;
        }
        $v          = ucfirst($v);
        $controller .= '\'app\\' . $module . '\\controller\\' . $v . '\',' . "\r\n";
        if ($config['module'][$module]) {
            $route .= 'Route::resource(\'' . strtolower($v) . '\', \'' . $module . '/' . $v . '\');' . "\r\n";
        } else {
            $route .= 'Route::resource(\'' . strtolower($module . '/' . $v) . '\', \'' . $module . '/' . $v . '\');' . "\r\n";
        }
    }
    $data = str_replace(array('{var_doc}', '{var_route}'), array($controller, $route), $data);
    file_put_contents($path . 'other.txt', $data);
    echo $path . 'other.txt----------create succeed<br/>';
}

function get_controller() {
    static $controller;
    if (empty($controller)) {
        $controller = array();
        $tables     = get_tables();
        foreach ($tables as $v) {
            $controller[] = $v['hump'];
        }
    }
    return $controller;
}

function create_controller($module, $table, $short_route, $common) {
    $path = APP_PATH . $module . DS . 'controller' . DS;
    mkdirs($path);
    $data        = <<<'DATA'
<?php

namespace app\{var_module}\controller;

use think\Request;

/**
 * @title {var_title}
 * @group {var_group}
 */
class {var_class} extends {var_common} {

    /**
     * @title 列表
     * @url /{var_route}
     * @method get
     {var_list_params}
     * @param name:limit type:int require:0 default:15 desc:每页记录数
     * @param name:page type:int require:0 default:1 desc:获取的页码
     * @return total:总记录数
     * @return per_page:每页记录数
     * @return current_page:当前的页码
     * @return last_page:最后的页码
     * @return data:列表@
     * @data {var_list_data}
     * @author 开发者
     */
    public function index() {
        $m = new \app\{var_module}\model\{var_class}();
        $m->GetAll(request()->get());
    }

    /**
     * @title 依赖数据
     * @url /{var_route}/create
     * @method get
     * @return key:value
     * @author 开发者
     */
    public function create() {

    }

    /**
     * @title 添加
     * @url /{var_route}
     * @method post
     {var_field_params}
     * @author 开发者
     */
    public function save() {
        $m = new \app\{var_module}\model\{var_class}();
        $m->AddOne(request()->post());
    }

    /**
     * @title 删除
     * @url /{var_route}/:id
     * @method delete
     * @author 开发者
     */
    public function delete($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\{var_module}\model\{var_class}();
        $m->DelOne($id);
    }

    /**
     * @title 编辑
     * @url /{var_route}/:id
     * @method put
     {var_field_params}
     * @author 开发者
     */
    public function update(Request $request, $id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\{var_module}\model\{var_class}();
        $m->EditOne($request->put(), $id);
    }

    /**
     * @title 读取
     * @url /{var_route}/:id
     * @method get
     {var_field_return}
     * @author 开发者
     */
    public function read($id) {
        if ($id < 1) {
            show_json(0, '参数ID错误');
        }
        $m = new \app\{var_module}\model\{var_class}();
        $m->GetOne($id);
    }

}
DATA;
    $route       = strtolower(($short_route ? '' : ($module . '/')) . $table['hump']);
    $list_params = array();
    if ($table['exist_createtime'] || $table['exist_updatetime']) {
        $list_params[] = 'name:starttime type:string require:0 default:- other:- desc:开始时间(年-月-日_时:分:秒)';
        $list_params[] = 'name:endtime type:string require:0 default:- other:- desc:结束时间(年-月-日_时:分:秒)';
    }
    $table['exist_status'] && $list_params[] = 'name:status type:int require:0 default:- other:- desc:' . $table['exist_status'];
    $table['exist_enabled'] && $list_params[] = 'name:enabled type:int require:0 default:- other:- desc:' . $table['exist_enabled'];
    $table['exist_type'] && $list_params[] = 'name:type type:int require:0 default:- other:- desc:' . $table['exist_type'];
    $table['keywords'] && $list_params[] = 'name:keyword type:string require:0 default:- other:- desc:关键字检索';
    $field_params = array();
    $field_list   = array();
    foreach ($table['field'] as $field) {
        if (!in_array($field['0'], array('id', 'createtime', 'updatetime'))) {
            $field_params[] = 'name:' . $field[0] . ' type:' . $field[1] . ' require:1 default:- other:- desc:' . $field[2];
        }
        $field_list[] = $field[0] . ':' . $field[2];
    }
    $search  = array('{var_module}', '{var_title}', '{var_group}', '{var_class}', '{var_common}', '{var_route}', '{var_list_params}', '{var_list_data}', '{var_field_params}', '{var_field_return}');
    $replace = array($module, $table['comment'], strtoupper($module), $table['hump'], $common, $route, '* @param ' . implode("\r\n" . '     * @param ', $list_params), implode(' ', $field_list), '* @param ' . implode("\r\n" . '     * @param ', $field_params), '* @return ' . implode("\r\n" . '     * @return ', $field_list));
    $data    = str_replace($search, $replace, $data);
    file_put_contents($path . $table['hump'] . '.php', $data);
    echo $path . $table['hump'] . '.php----------create succeed<br/>';
}

function create_model($module, $table) {
    $path = APP_PATH . $module . DS . 'model' . DS;
    mkdirs($path);
    $data    = <<<'DATA'
<?php

namespace app\{var_module}\model;

class {var_class} extends Common {

    public function GetAll($params) {
        $map = array();
        {var_map}
        $list = $this->where($map)->paginate($params['limit'])->toArray();
        if (!empty($list['data'])) {
            foreach ($list['data'] as $k => &$item) {
                {var_list_item}
            }
            unset($item);
        }
        show_json(1, $list);
    }

    public function AddOne($params) {
        $data = array(
            {var_add_params}
        );
        $this->checkData($data, 0);
        if ($this->data($data, true)->isUpdate(false)->save()) {
            //logs('创建新的??,ID:' . $this->getLastInsID(), 1);
            show_json(1, '添加成功');
        } else {
            show_json(0, '添加失败');
        }
    }

    private function checkData(&$data, $id = 0) {
        //TODO 数据校验
    }

    public function DelOne($id) {
        if ($this->where(array('id' => $id))->delete()) {
            //logs('删除??,ID:' . $id, 2);
            show_json(1, '删除成功');
        } else {
            show_json(0, '删除失败');
        }
    }

    public function EditOne($params, $id) {
        $data = array(
            {var_edit_params}
        );
        $this->checkData($data, $id);
        if ($this->save($data, array('id' => $id)) !== false) {
            //logs('编辑??,ID:' . $id, 3);
            show_json(1, '编辑成功');
        } else {
            show_json(0, '编辑失败');
        }
    }

    public function GetOne($id) {
        $item = $this->get($id);
        if (empty($item)) {
            show_json(1);
        } else {
            $item = $item->toArray();
            {var_one_item}
        }
        show_json(1, $item);
    }

}
DATA;
    $var_map = array();
    if ($table['exist_createtime']) {
        $var_map[] = 'if (!empty($params[\'starttime\']) && !empty($params[\'endtime\'])) {
            $map[\'createtime\'] = array(\'between\', strtotime($params[\'starttime\']) . \',\' . strtotime($params[\'endtime\']));
        }';
    }
    if (empty($table['exist_createtime']) && $table['exist_updatetime']) {
        $var_map[] = 'if (!empty($params[\'starttime\']) && !empty($params[\'endtime\'])) {
            $map[\'updatetime\'] = array(\'between\', strtotime($params[\'starttime\']) . \',\' . strtotime($params[\'endtime\']));
        }';
    }
    $table['exist_status'] && $var_map[] = 'if (isset($params[\'status\']) && $params[\'status\'] !== \'\') {
            $map[\'status\'] = intval($params[\'status\']);
        }';
    $table['exist_enabled'] && $var_map[] = 'if (isset($params[\'enabled\']) && $params[\'enabled\'] !== \'\') {
            $map[\'enabled\'] = intval($params[\'enabled\']);
        }';
    $table['exist_type'] && $var_map[] = 'if (isset($params[\'type\']) && $params[\'type\'] !== \'\') {
            $map[\'type\'] = intval($params[\'type\']);
        }';
    $table['keywords'] && $var_map[] = 'if (!empty($params[\'keyword\'])) {
            $map[\'' . $table['keywords'] . '\'] = array(\'LIKE\', \'%\' . trim($params[\'keyword\']) . \'%\');
        }';
    $var_item = array();
    $table['exist_createtime'] && $var_item[] = '$item[\'createtime\'] = date(\'Y-m-d H:i:s\', $item[\'createtime\']);';
    $table['exist_updatetime'] && $var_item[] = '$item[\'updatetime\'] = date(\'Y-m-d H:i:s\', $item[\'updatetime\']);';
    empty($var_item) && $var_item[] = '//TODO 进行数据处理';
    $var_params = array();
    foreach ($table['field'] as $field) {
        if (in_array($field['0'], array('id', 'createtime', 'updatetime'))) {
            continue;
        }
        if ($field[1] == 'int') {
            $var_params[] = '\'' . $field['0'] . '\' => intval($params[\'' . $field['0'] . '\']),';
        } elseif ($field[1] == 'float') {
            $var_params[] = '\'' . $field['0'] . '\' => trim($params[\'' . $field['0'] . '\']) * 1,';
        } else {
            $var_params[] = '\'' . $field['0'] . '\' => trim($params[\'' . $field['0'] . '\']),';
        }
    }
    $table['exist_updatetime'] && $var_params[] = '\'updatetime\' => time(),';
    $var_add_params = $var_params;
    $table['exist_createtime'] && $var_add_params[] = '\'createtime\' => time(),';
    $search  = array('{var_module}', '{var_class}', '{var_map}', '{var_list_item}', '{var_add_params}', '{var_edit_params}', '{var_one_item}');
    $replace = array($module, $table['hump'], implode("\r\n" . '        ', $var_map), implode("\r\n" . '                ', $var_item), implode("\r\n" . '            ', $var_add_params), implode("\r\n" . '            ', $var_params), implode("\r\n" . '            ', $var_item));
    $data    = str_replace($search, $replace, $data);
    file_put_contents($path . $table['hump'] . '.php', $data);
    echo $path . $table['hump'] . '.php----------create succeed<br/>';
}

function build() {
    $config = get_config();
    if (empty($config['run'])) {
        die('not run');
    }
    echo '----------build start----------<br/><br/>';
    $tables = get_tables();
    foreach ($config['module'] as $module => $short_route) {
        echo '----------module *' . $module . '* start----------<br/><br/>';
        if ($config['create_common']) {
            create_common($module);
            create_common2($module);
        }
        if ($config['create_other']) {
            create_other($module);
        }
        foreach ($tables as $table) {
            if (in_array($table['hump'], $config['tables'])) {
                continue;
            }
            create_controller($module, $table, $short_route, $config['common']);
            create_model($module, $table);
        }
        echo '<br/>----------module *' . $module . '* end----------<br/><br/>';
    }
    echo '----------build finish----------<br/>';
}

build();