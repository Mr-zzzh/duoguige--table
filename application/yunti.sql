-- 用户表
DROP TABLE IF EXISTS `yunti_user`;
CREATE TABLE `yunti_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(30) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(30) NOT NULL COMMENT '电话',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` varchar(30) NOT NULL COMMENT '随机加盐',
  `intro` text COMMENT '简介',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '审核状态 0待审 1通过 2不通过',
  `type` int(2) NOT NULL DEFAULT '1' COMMENT '用户类型 1,普通用户,2技术大师,3物业公司',
  `token` varchar(40) NOT NULL COMMENT '用户标识',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `normal` int(2) DEFAULT '1' COMMENT '是否启用_1启用_2禁用',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `yunti_user` add  `remark` varchar(255) DEFAULT NULL COMMENT '审核备注';
ALTER TABLE `yunti_user` add  `checktime` int(11) DEFAULT NULL COMMENT '审核时间';

-- 技术大师认证表
DROP TABLE IF EXISTS `yunti_technician`;
CREATE TABLE `yunti_technician` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `name` varchar(30) NOT NULL COMMENT '真实姓名',
  `sex` int(2) NOT NULL COMMENT '性别1男2女',
  `idcardno` varchar(20) NOT NULL COMMENT '身份证号码',
  `company_name` varchar(30) NOT NULL COMMENT '公司名称',
  `license_number` varchar(50) NOT NULL COMMENT '公司营业执照号码',
  `company_image` varchar(255) NOT NULL COMMENT '公司营业执照照片',
  `prove_image` varchar(255) NOT NULL COMMENT '在职证明图片',
  `technician_image` varchar(255) NOT NULL COMMENT '技师证件',
  `dimission` varchar(255) NOT NULL COMMENT '离职证明图',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 物业企业认证表
DROP TABLE IF EXISTS `yunti_company`;
CREATE TABLE `yunti_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `company_name` varchar(30) NOT NULL COMMENT '公司名称',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `name` varchar(20) NOT NULL COMMENT '法人姓名',
  `area` varchar(50) NOT NULL COMMENT '公司地址省市区',
  `address` varchar(100) NOT NULL COMMENT '公司详细地址',
  `number` int(10) NOT NULL COMMENT '电梯数量',
  `brand` varchar(100) NOT NULL COMMENT '电梯品牌',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `image` varchar(255) DEFAULT NULL COMMENT '营业执照',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 收货地址管理
DROP TABLE IF EXISTS `yunti_delivery_address`;
CREATE TABLE `yunti_delivery_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL COMMENT '收货人电话',
  `area` varchar(50) DEFAULT NULL COMMENT '地区',
  `address` varchar(50) NOT NULL COMMENT '地址',
  `default` int(1) NOT NULL COMMENT '是否默认 0否 1是',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 新闻/行业动态表
DROP TABLE IF EXISTS `yunti_news`;
CREATE TABLE `yunti_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `type` int(2) DEFAULT NULL COMMENT '1图文2视频',
  `video` varchar(255) DEFAULT NULL COMMENT '视频链接',
  `content` text COMMENT '内容',
  `view_number` int(10) DEFAULT NULL COMMENT '浏览量',
  `like_number` int(10) DEFAULT NULL COMMENT '点赞量',
  `sort` int(10) DEFAULT NULL COMMENT '排序(序号越小越靠前)',
  `status` int(2) DEFAULT '1' COMMENT '状态_1显示_2不显示',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 留言表
DROP TABLE IF EXISTS `yunti_leave_message`;
CREATE TABLE `yunti_leave_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `nid` int(11) NOT NULL COMMENT '新闻或视频id',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1新闻留言2视频留言',
  `content` varchar(255) NOT NULL COMMENT '留言内容',
  `like_number` int(10) DEFAULT NULL COMMENT '点赞数量',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 点赞记录表
DROP TABLE IF EXISTS `yunti_like`;
CREATE TABLE `yunti_like` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `nid` int(11) NOT NULL COMMENT '新闻id或者视频id或者留言id',
  `type` int(2) DEFAULT NULL COMMENT '类型1新闻2留言3视频',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 问题表
DROP TABLE IF EXISTS `yunti_question`;
CREATE TABLE `yunti_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '提问人id',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `thumb` varchar(255) DEFAULT NULL COMMENT '图片(多张用逗号拼接)',
  `type` int(2) NOT NULL COMMENT '提问类型 1问答模块2大师提问',
  `master_id` int(2) DEFAULT NULL COMMENT '大师id(type为2时必填)',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `master_id` (`master_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 回答表
DROP TABLE IF EXISTS `yunti_answer`;
CREATE TABLE `yunti_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `answer` varchar(255) NOT NULL COMMENT '回答',
  `qid` int(11) NOT NULL COMMENT '问题id',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态 1显示 2隐藏',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 期望薪资设置表
DROP TABLE IF EXISTS `yunti_salary`;
CREATE TABLE `yunti_salary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) NOT NULL COMMENT '内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 工作年限设置表
DROP TABLE IF EXISTS `yunti_experience`;
CREATE TABLE `yunti_experience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 招聘表
DROP TABLE IF EXISTS `yunti_invite`;
CREATE TABLE `yunti_invite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '发布招聘用户id',
  `post` varchar(30) NOT NULL COMMENT '招聘岗位',
  `education` varchar(30) DEFAULT NULL COMMENT '学历',
  `salary` int(5) NOT NULL COMMENT '工资范围id',
  `experience` int(5) NOT NULL COMMENT '工作经验id',
  `province` int(10) NOT NULL COMMENT '省编号',
  `city` int(10) NOT NULL COMMENT '市编号',
  `area` int(10) DEFAULT NULL COMMENT '区编号',
  `description` text NOT NULL COMMENT '岗位描述',
  `duty` text NOT NULL COMMENT '岗位职责',
  `name` varchar(30) NOT NULL COMMENT '联系人姓名',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `status` int(2) NOT NULL COMMENT '状态 0待审 1通过 2不通过 3招聘结束',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `checktime` int(11) DEFAULT NULL COMMENT '审核时间',
  `address` varchar(50) DEFAULT NULL COMMENT '详细地址',
  `number` varchar(20) DEFAULT NULL COMMENT '招聘人数',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `city` (`city`),
  KEY `province` (`province`),
  KEY `salary` (`salary`),
  KEY `experience` (`experience`),
  KEY `area` (`area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `yunti_invite` add  `remark` varchar(255) DEFAULT NULL COMMENT '审核备注';

-- 求职表
DROP TABLE IF EXISTS `yunti_job_wanted`;
CREATE TABLE `yunti_job_wanted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `post` varchar(50) NOT NULL COMMENT '求职岗位',
  `salary` int(5) NOT NULL COMMENT '期望薪资',
  `arrival` varchar(50) NOT NULL COMMENT '到岗时间',
  `province` int(10) NOT NULL COMMENT '省编号',
  `city` int(10) NOT NULL COMMENT '市编号',
  `area` int(10) DEFAULT NULL COMMENT '区编号',
  `intro` text NOT NULL COMMENT '自我描述',
  `status` int(2) NOT NULL COMMENT '状态 0待审 1通过 2不通过 3已找到工作',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `checktime` int(11) DEFAULT NULL COMMENT '审核时间',
  `education` varchar(50) DEFAULT NULL COMMENT '最高学历',
  `name` varchar(50) DEFAULT NULL COMMENT '求职者姓名',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `area` (`area`),
  KEY `salary` (`salary`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `yunti_job_wanted` add  `remark` varchar(255) DEFAULT NULL COMMENT '审核备注';

-- 品牌表
DROP TABLE IF EXISTS `yunti_brand`;
CREATE TABLE `yunti_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(30) NOT NULL COMMENT '品牌名',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 品牌资料表
DROP TABLE IF EXISTS `yunti_brand_datum`;
CREATE TABLE `yunti_brand_datum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `bid` int(11) NOT NULL COMMENT '品牌id',
  `name` varchar(50) DEFAULT NULL COMMENT '资料标题',
  `datum` varchar(255) NOT NULL COMMENT '资料路由',
  `size` varchar(20) DEFAULT NULL COMMENT '大小',
  `view` int(10) DEFAULT NULL COMMENT '浏览量',
  `download` int(10) DEFAULT NULL COMMENT '下载量',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 我的下载表
DROP TABLE IF EXISTS `yunti_download`;
CREATE TABLE `yunti_download` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `bdid` int(11) NOT NULL COMMENT ' 资料id',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bdid` (`bdid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 故障库表
DROP TABLE IF EXISTS `yunti_fault`;
CREATE TABLE `yunti_fault` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fault_code` varchar(50) NOT NULL COMMENT '故障代码',
  `bid` int(11) NOT NULL COMMENT '品牌id',
  `models` varchar(255) DEFAULT NULL COMMENT '适用机型',
  `paraphrase` varchar(255) DEFAULT NULL COMMENT '代码释义',
  `dispose` varchar(255) DEFAULT NULL COMMENT '处理办法',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 维保单表
DROP TABLE IF EXISTS `yunti_maintenance`;
CREATE TABLE `yunti_maintenance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `brand` varchar(50) NOT NULL COMMENT '电梯品牌',
  `model` varchar(50) DEFAULT NULL COMMENT '型号',
  `floor_number` int(5) DEFAULT NULL COMMENT '楼层数',
  `type` varchar(30) DEFAULT NULL COMMENT '维修类型',
  `company` varchar(50) DEFAULT NULL COMMENT '单位名称',
  `province` int(10) DEFAULT NULL COMMENT '省编号',
  `city` int(10) DEFAULT NULL COMMENT '市编号',
  `area` int(10) DEFAULT NULL COMMENT '区编号',
  `address` varchar(100) DEFAULT NULL COMMENT '地址',
  `genre` int(2) DEFAULT NULL COMMENT '类型_1维修单_2保养单',
  `status` int(2) NOT NULL COMMENT '-1取消0待审 1审核通过 2不通过 3已接单 4已完成 5投诉 6投诉已处理',
  `checktime` int(11) DEFAULT NULL COMMENT '审核时间',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `canceltime` int(11) DEFAULT NULL COMMENT '取消时间',
  `finishtime` int(11) DEFAULT NULL COMMENT '完成时间',
  `receive_id` int(10) DEFAULT NULL COMMENT '接取保单师傅id',
  `receive_time` int(11) DEFAULT NULL COMMENT '接取时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `receive_id` (`receive_id`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `area` (`area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `yunti_maintenance` add  `remark` varchar(255) DEFAULT NULL COMMENT '审核备注';
ALTER TABLE `yunti_maintenance` add  `complete_time` varchar(255) DEFAULT NULL COMMENT '技术大师完成时间';

-- 维保单进度表
DROP TABLE IF EXISTS `yunti_plan`;
CREATE TABLE `yunti_plan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '维保单id',
  `plan` varchar(255) DEFAULT NULL COMMENT '进度',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 维保单评价表
DROP TABLE IF EXISTS `yunti_evaluate`;
CREATE TABLE `yunti_evaluate` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `mid` int(11) DEFAULT NULL COMMENT '维保单id',
  `start` varchar(20) DEFAULT NULL COMMENT '星星数',
  `content` varchar(255) DEFAULT NULL COMMENT '评价内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 维保单投诉表
DROP TABLE IF EXISTS `yunti_complaint`;
CREATE TABLE `yunti_complaint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `mid` int(11) DEFAULT NULL COMMENT '维保单id',
  `content` varchar(255) DEFAULT NULL COMMENT '投诉内容',
  `thumb` varchar(255) DEFAULT NULL COMMENT '图片',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 领取维保单表
DROP TABLE IF EXISTS `yunti_draw`;
CREATE TABLE `yunti_draw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `mid` int(11) NOT NULL COMMENT '维保单id',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 商品分类表
DROP TABLE IF EXISTS `yunti_goods_cate`;
CREATE TABLE `yunti_goods_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '分类名',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 商品标签表
DROP TABLE IF EXISTS `yunti_goods_label`;
CREATE TABLE `yunti_goods_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '标签',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 商品表
DROP TABLE IF EXISTS `yunti_goods`;
CREATE TABLE `yunti_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '商品名',
  `subhead` varchar(255) DEFAULT NULL COMMENT '副标题',
  `sort` int(10) DEFAULT NULL COMMENT '排序(越小越靠前)',
  `bid` int(11) NOT NULL COMMENT '品牌id',
  `cid` int(11) NOT NULL COMMENT '商品分类id',
  `thumbnail` varchar(255) NOT NULL COMMENT '商品缩略图',
  `image` varchar(255) DEFAULT NULL COMMENT '详情轮播图',
  `specification` varchar(255) NOT NULL COMMENT '规格',
  `model` varchar(255) NOT NULL COMMENT '型号',
  `manufacturers` varchar(255) NOT NULL COMMENT '厂家名称',
  `phone` varchar(20) NOT NULL COMMENT '销售电话',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `label` varchar(255) NOT NULL COMMENT '标签',
  `intro` varchar(255) DEFAULT NULL COMMENT '详情',
  `area` varchar(255) DEFAULT NULL COMMENT '产地',
  `province` varchar(255) DEFAULT NULL COMMENT '省份',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `color` varchar(20) DEFAULT NULL COMMENT '颜色',
  `sale_number` int(10) DEFAULT NULL COMMENT '销量',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `yunti_goods` add  `view_number` int(10) DEFAULT NULL COMMENT '浏览量';

-- 订单表
DROP TABLE IF EXISTS `yunti_goods_order`;
CREATE TABLE `yunti_goods_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `gid` int(10) NOT NULL COMMENT '商品id',
  `ordersn` varchar(255) DEFAULT NULL COMMENT '订单号',
  `number` int(10) DEFAULT NULL COMMENT '商品数量',
  `money` decimal(10,2) DEFAULT NULL COMMENT '商品金额',
  `status` int(2) DEFAULT NULL COMMENT '-1取消订单 0待支付 1支付 2已发货 3已收货',
  `paytype` int(2) DEFAULT NULL COMMENT '1支付宝 2微信',
  `tradeno` int(11) DEFAULT NULL COMMENT '交易单号',
  `addressid` int(11) DEFAULT NULL COMMENT '地址id',
  `paytime` int(11) DEFAULT NULL COMMENT '支付时间',
  `finishtime` int(11) DEFAULT NULL COMMENT '完成时间',
  `canceltime` int(11) DEFAULT NULL COMMENT '取消时间',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `delivertime` int(11) DEFAULT NULL COMMENT '发货时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `gid` (`gid`),
  KEY `addressid` (`addressid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 发货提醒表
DROP TABLE IF EXISTS `yunti_remind`;
CREATE TABLE `yunti_remind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) DEFAULT NULL COMMENT '订单id',
  `status` int(2) DEFAULT NULL COMMENT '状态0未阅1已阅',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 意见反馈表
DROP TABLE IF EXISTS `yunti_feedback`;
CREATE TABLE `yunti_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `content` text COMMENT '反馈内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 设置表
DROP TABLE IF EXISTS `yunti_set`;
CREATE TABLE `yunti_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 搜索记录表
DROP TABLE IF EXISTS `yunti_search_history`;
CREATE TABLE `yunti_search_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `type` int(2) DEFAULT NULL COMMENT '类型_1首页搜索_2故障库搜索',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 后台用户表
DROP TABLE IF EXISTS `yunti_admin`;
CREATE TABLE `yunti_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) NOT NULL COMMENT '名称(账号)',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `salt` varchar(50) NOT NULL COMMENT '随机盐',
  `status` int(2) NOT NULL COMMENT '状态：0-禁用，1-启用',
  `token` varchar(255) NOT NULL COMMENT '用户token',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 后台角色表
DROP TABLE IF EXISTS `yunti_role`;
CREATE TABLE `yunti_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) DEFAULT NULL COMMENT '角色名称',
  `rule` text COMMENT '权限',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 地区表
DROP TABLE IF EXISTS `yunti_area`;
CREATE TABLE `yunti_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '级别',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `code` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '编码',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='省市区';

-- 轮播图表
DROP TABLE IF EXISTS `yunti_banner`;
CREATE TABLE `yunti_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `url` varchar(255) DEFAULT NULL COMMENT '图片url',
  `jumpurl` varchar(255) DEFAULT NULL COMMENT '跳转地址',
  `newsid` int(10) DEFAULT NULL COMMENT '新闻id(type为3时)',
  `sort` int(10) DEFAULT NULL COMMENT '排序(越小越靠前)',
  `type` tinyint(2) DEFAULT NULL COMMENT '类型_1首页轮播图_2保险页面图_3新闻页面轮播图',
  `status` int(10) DEFAULT '1' COMMENT '状态_1显示_2不显示',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 短信设置表
DROP TABLE IF EXISTS `yunti_note`;
CREATE TABLE `yunti_note` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appkey` varchar(255) DEFAULT NULL COMMENT '短信appkey',
  `tid` varchar(50) NOT NULL COMMENT '模板id',
  `code` varchar(255) DEFAULT NULL COMMENT '短信验证码变量',
  `service` varchar(30) DEFAULT NULL COMMENT '客服电话',
  `agreement` text COMMENT '协议',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 技师等级表
DROP TABLE IF EXISTS `yunti_grade`;
CREATE TABLE `yunti_grade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '等级名称',
  `score` int(10) DEFAULT NULL COMMENT '分数',
  `number` int(10) DEFAULT NULL COMMENT '接单数',
  `status` int(10) DEFAULT '1' COMMENT '状态_1开启_2关闭',
  `createtime` int(1) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 分享设置表
DROP TABLE IF EXISTS `yunti_share`;
CREATE TABLE `yunti_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '分享标题',
  `icon` varchar(255) DEFAULT NULL COMMENT '分享图标',
  `intro` varchar(255) DEFAULT NULL COMMENT '分享描述',
  `share_link` varchar(255) DEFAULT NULL COMMENT '分享链接',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;







-- 操作日志
DROP TABLE IF EXISTS `yunti_operation_log`;
CREATE TABLE `coa_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL COMMENT '公司ID',
  `mid` int(10) unsigned NOT NULL COMMENT '操作人id',
  `module` varchar(50) NOT NULL DEFAULT '' COMMENT '应用',
  `controller` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '方法',
  `remark` text COMMENT '操作内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE coa_operation_log ADD content text COMMENT '操作详细内容(主要用于记录修改,删除的记录具体信息,转JSON存储)';



