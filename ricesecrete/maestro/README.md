![](https://www.thinkphp.cn/uploads/images/20230630/300c856765af4d8ae758c503185f8739.png)

ThinkPHP 8
===============

## 特性

* 基于PHP`8.0+`重构
* 升级`PSR`依赖
* 依赖`think-orm`3.0+版本
* 全新的`think-dumper`服务，支持远程调试
* 支持`6.0`/`6.1`无缝升级

> ThinkPHP8的运行环境要求PHP8.0+

现在开始，你可以使用官方提供的[ThinkChat](https://chat.topthink.com/)，让你在学习ThinkPHP的旅途中享受私人AI助理服务！

![](https://www.topthink.com/uploads/assistant/20230630/4d1a3f0ad2958b49bb8189b7ef824cb0.png)

ThinkPHP生态服务由[顶想云](https://www.topthink.com)（TOPThink Cloud）提供，为生态提供专业的开发者服务和价值之选。

## 文档

[完全开发手册](https://doc.thinkphp.cn)


## 赞助

全新的[赞助计划](https://www.thinkphp.cn/sponsor)可以让你通过我们的网站、手册、欢迎页及GIT仓库获得巨大曝光，同时提升企业的品牌声誉，也更好保障ThinkPHP的可持续发展。

[![](https://www.thinkphp.cn/sponsor/special.svg)](https://www.thinkphp.cn/sponsor/special)

[![](https://www.thinkphp.cn/sponsor.svg)](https://www.thinkphp.cn/sponsor)

## 安装

~~~
composer create-project topthink/think tp
~~~

启动服务

~~~
cd tp
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

如果需要更新框架使用
~~~
composer update topthink/framework
~~~

## 命名规范

`ThinkPHP`遵循PSR-2命名规范和PSR-4自动加载规范。

#常驻进程 
##广告位服务
php think worker_timer start
##队列服务
php think queue:listen

#投资流量包结算 5分钟
cd /www/wwwroot/omg/api/ && php think userfinancesettle

#每日用户推广统计 5分钟
cd /www/wwwroot/omg/api/ && php think usertasksummary

#语言翻译
cd /www/wwwroot/omg/api/ && php think lang_task

#更新广告位编码 1分钟
cd /www/wwwroot/omg/api/ &&  php think refresh_level_password

#pubmatic-邀请活动结算 5分钟
cd /www/wwwroot/omg/api/ && php think inviteactivity

#pubmatic-信用分加减
cd /www/wwwroot/omg/api && php think creditscorecount

#团队活跃奖励统计 2分钟
cd /www/wwwroot/omg/api/&& php think newteamactivereward

#针对活动-每日用户推广统计 1分钟
cd /www/wwwroot/omg/api/ && php think newusertasksummary
