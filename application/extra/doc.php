<?php
return [
    'title'         => "云梯APPAPi接口文档",  //文档title
    'version'       => '1.0.0', //文档版本
    'copyright'     => 'Powered By CCIA', //版权信息
    'password'      => '', //访问密码，为空不需要密码
    //静态资源路径--默认为云上路径，解决很多人nginx配置问题
    //可将assets目录拷贝到public下面，具体路径课自行配置
    'static_path'   => '/assets',
    'controller'    => [
        //需要生成文档的类
        //admin
        'app\admin\controller\Admin',
        'app\admin\controller\Brand',
        'app\admin\controller\Banner',
        'app\admin\controller\BrandDatum',
        'app\admin\controller\Salary',
        'app\admin\controller\Experience',
        'app\admin\controller\Fault',
        'app\admin\controller\Feedback',
        'app\admin\controller\Goods',
        'app\admin\controller\GoodsCate',
        'app\admin\controller\GoodsLabel',
        'app\admin\controller\GoodsOrder',
        'app\admin\controller\Invite',
        'app\admin\controller\JobWanted',
        'app\admin\controller\Maintenance',
        'app\admin\controller\News',
        'app\admin\controller\Payset',
        'app\admin\controller\Question',
        'app\admin\controller\Remind',
        //'app\admin\controller\Role',
        'app\admin\controller\Set',
        'app\admin\controller\User',

        //mobile
        'app\mobile\controller\Area',
        'app\mobile\controller\Brand',
        'app\mobile\controller\Company',
        'app\mobile\controller\DeliveryAddress',
        'app\mobile\controller\Fault',
        'app\mobile\controller\Feedback',
        'app\mobile\controller\Goods',
        'app\mobile\controller\GoodsOrder',
        'app\mobile\controller\Index',
        'app\mobile\controller\Invite',
        'app\mobile\controller\JobWanted',
        'app\mobile\controller\Maintenance',
        'app\mobile\controller\News',
        'app\mobile\controller\Question',
        'app\mobile\controller\Technician',
        'app\mobile\controller\User',
    ],
    'filter_method' => [
        //过滤 不解析的方法名称
        '_empty'
    ],
    'return_format' => [
        //数据格式
        'status'  => '1/0/-1/-2/-3',
        'message' => '操作成功/操作失败/系统异常/路由未定义/您尚未登录',
    ],
    'public_header' => [
        //全局公共头部参数
        //如：['name'=>'version', 'require'=>1, 'default'=>'', 'desc'=>'版本号(全局)']
        ['name' => 'token', 'require' => 1, 'default' => '', 'desc' => '登录标识']
    ],
    'public_param'  => [
        //全局公共请求参数，设置了所以的接口会自动增加次参数
        //如：['name'=>'token', 'type'=>'string', 'require'=>1, 'default'=>'', 'other'=>'' ,'desc'=>'验证（全局）')']
    ],
];
