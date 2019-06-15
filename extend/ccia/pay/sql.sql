-- ----------------------------
-- Table structure for `pay_set`
-- 支付配置表
-- ----------------------------
DROP TABLE IF EXISTS `pay_set`;
CREATE TABLE `pay_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paytype` tinyint(3) NOT NULL DEFAULT '1' COMMENT '支付方式 1-阿里支付  2-微信app支付 3-微信公众号|H5支付 4-微信小程序支付',
  `alipay_appId` varchar(50) NOT NULL DEFAULT '' COMMENT '阿里支付appid',
  `alipay_gatewayUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '阿里支付网关',
  `alipay_rsaPrivateKey` text COMMENT '阿里支付私钥',
  `alipay_alipayrsaPublicKey` text COMMENT '阿里支付公钥',
  `wxpay_APPID` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付appid',
  `wxpay_MCHID` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付商户号',
  `wxpay_KEY` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付key',
  `wxpay_APPSECRET` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付应用密钥',
  `wxpay_apiclient_cert` text COMMENT '微信cert',
  `wxpay_apiclient_key` text COMMENT '微信key证书',
  `wxpay_SSLCERT_PATH` varchar(255) NOT NULL DEFAULT '' COMMENT '微信cert路径',
  `wxpay_SSLKEY_PATH` varchar(255) NOT NULL DEFAULT '' COMMENT '微信key证书路径',
  `createtime` int(10) unsigned NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of pay_set
-- ----------------------------

-- ----------------------------
-- Table structure for `pay_log`
-- 支付记录表
-- ----------------------------
DROP TABLE IF EXISTS `pay_log`;
CREATE TABLE `pay_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordersn` varchar(50) DEFAULT '' COMMENT '订单号',
  `ordermoney` decimal(10,2) DEFAULT '0.00' COMMENT '订单支付金额',
  `paymoney` decimal(10,2) DEFAULT '0.00' COMMENT '实际支付金额',
  `paystatus` tinyint(1) DEFAULT 0 COMMENT '支付状态-2-已退款-1-取消关闭0-待支付1-已支付',
  `paytime` int(11) DEFAULT 0 COMMENT '支付时间',
  `createtime` int(11) DEFAULT 0 COMMENT '支付创建时间',
  `trade_no` varchar(50) DEFAULT '' COMMENT '商家订单号',
  `paytype` tinyint(1) DEFAULT '0' COMMENT '支付方式1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of pay_log
-- ----------------------------
-- ----------------------------
-- Table structure for `refund_log`
-- 支付记录表
-- ----------------------------
DROP TABLE IF EXISTS `refund_log`;
CREATE TABLE `refund_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `refundsn` varchar(50) DEFAULT '' COMMENT '订单号',
  `ordersn` varchar(50) DEFAULT '' COMMENT '订单号',
  `ordermoney` decimal(10,2) DEFAULT '0.00' COMMENT '订单支付金额',
  `refundmoney` decimal(10,2) DEFAULT '0.00' COMMENT '实际支付金额',
  `refundstatus` tinyint(1) DEFAULT 0 COMMENT '支付状态-1-取消退款0-待退款1-已退款',
  `refundtime` int(11) DEFAULT 0 COMMENT '退款时间',
  `createtime` int(11) DEFAULT 0 COMMENT '退款创建时间',
  `paytype` tinyint(1) DEFAULT '0' COMMENT '支付方式1-阿里支付2-微信app支付3-微信H5公众号支付4-微信小程序支付5-余额支付',
  `reason` varchar(255) DEFAULT '' COMMENT '退款说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of refund_log
-- ----------------------------