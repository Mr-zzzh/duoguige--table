#!/bin/sh
count=`ps -ef|grep /www/wdlinux/phps/71/bin/php /www/web/yunti/public_html/index.php admin/Task/order >> /www/web/ccgg/public_html/order.log|grep -v grep|wc -l`
if [ ${count} -eq 0 ];then
/www/wdlinux/phps/71/bin/php /www/web/yunti/public_html/index.php admin/Task/order >> /www/web/ccgg/public_html/order.log
fi