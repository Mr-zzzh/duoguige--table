#yunti

定时任务处理
/sbin/service crond start   启动
/sbin/service crond stop	停止
crontab -e                  编辑定时任务
*/5 * * * *  /bin/sh /www/web/yunti/public_html/order.sh
