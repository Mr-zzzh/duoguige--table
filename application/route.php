<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::miss('mobile/Common/miss');
//文件上传
Route::rule('upload', 'admin/Admin/upload', 'POST');
//富文本框
Route::rule('ue_upload', 'admin/Admin/ue_upload', 'GET|POST|OPTIONS');
//后台
Route::resource('admin/admin', 'admin/Admin');
Route::rule('admin/register', 'admin/Admin/register', 'GET');
Route::rule('admin/login', 'admin/Admin/login', 'POST|GET');

Route::resource('admin/brand', 'admin/Brand');
Route::resource('admin/banner', 'admin/Banner');
Route::resource('admin/branddatum', 'admin/BrandDatum');
Route::resource('admin/salary', 'admin/Salary');
Route::resource('admin/experience', 'admin/Experience');
Route::resource('admin/fault', 'admin/Fault');
Route::resource('admin/feedback', 'admin/Feedback');
Route::resource('admin/goods', 'admin/Goods');
Route::resource('admin/goodscate', 'admin/GoodsCate');
Route::resource('admin/goodslabel', 'admin/GoodsLabel');
Route::resource('admin/goodsorder', 'admin/GoodsOrder');
Route::rule('admin/goodsorder/deliver', 'admin/GoodsOrder/deliver', 'POST|GET');
Route::rule('admin/goodsorder/trend_chart', 'admin/GoodsOrder/trend_chart', 'GET');

Route::resource('admin/invite', 'admin/Invite');
Route::rule('admin/invite/editstatus', 'admin/Invite/editstatus', 'POST|GET');
Route::resource('admin/jobwanted', 'admin/JobWanted');
Route::rule('admin/jobwanted/editstatus', 'admin/JobWanted/editstatus', 'POST|GET');
Route::resource('admin/maintenance', 'admin/Maintenance');
Route::rule('admin/maintenance/editstatus', 'admin/Maintenance/editstatus', 'GET|POST');

Route::resource('admin/news', 'admin/News');

Route::resource('admin/question', 'admin/Question');
Route::rule('admin/question/answer', 'admin/Question/answer', 'GET');
Route::rule('admin/question/delete_answer', 'admin/Question/delete_answer', 'POST');
Route::rule('admin/question/edit_status', 'admin/Question/edit_status', 'GET|POST');

Route::resource('admin/role', 'admin/Role');
Route::resource('admin/set', 'admin/Set');
Route::resource('admin/user', 'admin/User');
Route::rule('admin/user/editstatus', 'admin/User/editstatus', 'POST|GET');
Route::rule('admin/user/forbidden', 'admin/User/forbidden', 'POST|GET');
Route::rule('admin/payset/index', 'admin/Payset/index', 'POST|GET');
Route::rule('admin/payset/add', 'admin/Payset/add', 'POST|GET');
Route::rule('admin/payset/payget', 'admin/Payset/payget', 'POST|GET');
Route::rule('admin/payset/delete', 'admin/Payset/delete', 'POST|GET');

//手机端
Route::rule('login', 'mobile/Index/login', 'POST');
Route::rule('home', 'mobile/Index/home', 'GET|POST');
//文件上传
Route::rule('mobile/upload', 'mobile/Index/upload', 'POST');
Route::resource('answer', 'mobile/Answer');
Route::resource('area', 'mobile/Area');
Route::resource('brand', 'mobile/Brand');
Route::resource('branddatum', 'mobile/BrandDatum');
Route::resource('company', 'mobile/Company');
Route::resource('deliveryaddress', 'mobile/DeliveryAddress');
Route::resource('download', 'mobile/Download');
Route::resource('fault', 'mobile/Fault');
Route::resource('feedback', 'mobile/Feedback');
Route::resource('goods', 'mobile/Goods');
Route::resource('goodscate', 'mobile/GoodsCate');
Route::resource('goodslabel', 'mobile/GoodsLabel');
Route::resource('goodsorder', 'mobile/GoodsOrder');
Route::resource('invite', 'mobile/Invite');
Route::resource('jobwanted', 'mobile/JobWanted');
Route::resource('leavemessage', 'mobile/LeaveMessage');
Route::resource('like', 'mobile/Like');
Route::resource('maintenance', 'mobile/Maintenance');
Route::resource('news', 'mobile/News');
Route::resource('question', 'mobile/Question');
Route::resource('receive', 'mobile/Receive');
Route::resource('role', 'mobile/Role');
Route::resource('searchhistory', 'mobile/SearchHistory');
Route::resource('set', 'mobile/Set');
Route::resource('technician', 'mobile/Technician');
Route::resource('user', 'mobile/User');
Route::rule('user/code', 'mobile/User/code', 'POST');
