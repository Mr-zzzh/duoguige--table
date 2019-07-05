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
Route::rule('admin/index', 'admin/Admin/admin', 'GET');
Route::rule('admin/summarize', 'admin/Admin/summarize', 'GET');
Route::rule('admin/market', 'admin/Admin/market', 'GET');

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
Route::rule('admin/goodsorder/summarize', 'admin/GoodsOrder/summarize', 'GET');
Route::resource('admin/grade', 'admin/Grade');
//招聘求职
Route::resource('admin/invite', 'admin/Invite');
Route::rule('admin/invite/editstatus', 'admin/Invite/editstatus', 'POST|GET');
Route::resource('admin/jobwanted', 'admin/JobWanted');
Route::rule('admin/jobwanted/editstatus', 'admin/JobWanted/editstatus', 'POST|GET');
Route::resource('admin/maintenance', 'admin/Maintenance');
Route::rule('admin/maintenance/editstatus', 'admin/Maintenance/editstatus', 'GET|POST');

Route::resource('admin/news', 'admin/News');
Route::resource('admin/note', 'admin/Note');
//问答
Route::resource('admin/question', 'admin/Question');
Route::rule('admin/question/answer', 'admin/Question/answer', 'GET');
Route::rule('admin/question/delete_answer', 'admin/Question/delete_answer', 'POST');
Route::rule('admin/question/edit_status', 'admin/Question/edit_status', 'GET|POST');
Route::resource('admin/remind', 'admin/Remind');
Route::rule('admin/remind/unreadnum', 'admin/Remind/unreadnum', 'GET|POST');
//Route::resource('admin/role', 'admin/Role');
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
Route::rule('login_code', 'mobile/Index/login_code', 'POST');
Route::rule('register', 'mobile/User/register', 'POST');
Route::rule('home', 'mobile/Index/home', 'GET|POST');
Route::rule('city', 'mobile/Index/city', 'GET|POST');
Route::rule('insurance', 'mobile/Index/insurance', 'GET|POST');
Route::rule('search', 'mobile/Index/search', 'GET|POST');
Route::rule('history', 'mobile/Index/history', 'GET');
Route::rule('history_del', 'mobile/Index/history_del', 'POST');
//文件上传
Route::rule('mobile/upload', 'mobile/Index/upload', 'POST');
Route::resource('area', 'mobile/Area');
//品牌
Route::resource('brand', 'mobile/Brand');
Route::rule('branddatum', 'mobile/Brand/branddatum', 'POST|GET');
Route::rule('brand/collect', 'mobile/Brand/collect', 'POST|GET');

Route::resource('company', 'mobile/Company');
Route::resource('deliveryaddress', 'mobile/DeliveryAddress');
//故障库
Route::resource('fault', 'mobile/Fault');
Route::rule('transition', 'mobile/Fault/transition', 'GET|POST');
Route::resource('feedback', 'mobile/Feedback');
Route::resource('goods', 'mobile/Goods');
Route::rule('goodscate', 'mobile/Goods/goodscate');
Route::resource('goodsorder', 'mobile/GoodsOrder');
Route::rule('goodsorder/affirm', 'mobile/GoodsOrder/affirm', 'GET|POST');
Route::rule('goodsorder/pay', 'mobile/GoodsOrder/pay', 'POST');

Route::resource('invite', 'mobile/Invite');
Route::rule('salary', 'mobile/Invite/salary', 'GET');
Route::rule('experience', 'mobile/Invite/experience', 'GET');
Route::resource('jobwanted', 'mobile/JobWanted');
Route::resource('maintenance', 'mobile/Maintenance');
Route::rule('evaluate', 'mobile/Maintenance/evaluate', 'POST|GET');
Route::rule('complaint', 'mobile/Maintenance/complaint', 'POST|GET');
Route::rule('maintenance/status_edit', 'mobile/Maintenance/status_edit', 'POST');
Route::rule('allevaluate', 'mobile/Maintenance/allevaluate', 'GET');
Route::rule('task_hall', 'mobile/Maintenance/task_hall', 'GET');
Route::rule('my_task', 'mobile/Maintenance/my_task', 'GET');
Route::rule('task_detail', 'mobile/Maintenance/task_detail', 'GET');
Route::rule('plan', 'mobile/Maintenance/plan', 'POST');
Route::rule('receive_task', 'mobile/Maintenance/receive_task', 'POST');
Route::rule('complaint_detail', 'mobile/Maintenance/complaint_detail', 'GET');

Route::resource('news', 'mobile/News');
Route::rule('comment', 'mobile/News/leavemessage', 'GET');
Route::rule('comment_add', 'mobile/News/comment_add', 'POST');
Route::rule('like', 'mobile/News/like', 'POST');
//问答管理
Route::resource('question', 'mobile/Question');
Route::rule('answer', 'mobile/Question/answer', 'GET');
Route::rule('response', 'mobile/Question/response', 'POST');
Route::rule('my_question', 'mobile/Question/my_question', 'GET');
Route::rule('my_answer', 'mobile/Question/my_answer', 'GET');

Route::resource('technician', 'mobile/Technician');
Route::rule('technician/question', 'mobile/Technician/question', 'GET');
Route::rule('technician/question_add', 'mobile/Technician/question_add', 'POST');

Route::resource('user', 'mobile/User');
Route::rule('user/code', 'mobile/User/code', 'POST');
Route::rule('my_collect', 'mobile/User/my_collect', 'GET');
Route::rule('collect_del', 'mobile/User/collect_del', 'POST');
Route::rule('my_like', 'mobile/User/my_like', 'GET');
Route::rule('user/technician_add', 'mobile/User/technician_add', 'POST');
Route::rule('user/technician_edit', 'mobile/User/technician_edit', 'POST');
Route::rule('user/approve_detail', 'mobile/User/approve_detail', 'GET');
Route::rule('password_edit', 'mobile/User/password_edit', 'POST');
