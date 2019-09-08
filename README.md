<center><img src="https://res.cloudinary.com/clover-cms/image/upload/v1567957620/clover_pljwc5.png" height="50"></center>
<center>
[![Build Status](https://travis-ci.org/donghaichen/framework.svg?branch=master)](https://travis-ci.org/donghaichen/framework)
[![Total Downloads](https://poser.pugx.org/clovers/framework/d/total.svg)](https://packagist.org/packages/clovers/framework)
[![Latest Stable Version](https://poser.pugx.org/clovers/framework/v/stable.svg)](https://packagist.org/packages/clovers/framework)
[![Latest Unstable Version](https://poser.pugx.org/clovers/framework/v/unstable.svg)](https://packagist.org/packages/clover/framework)
[![License](https://poser.pugx.org/clovers/framework/license.svg)](https://packagist.org/packages/clover/framework)
</center>

## 认识 Clover

	 为速度而生的 PHP 框架
	
	 专注于构建无状态 API ，JSON API 服务

#### 特性

1. 专注于构建无状态 API ，JSON API 服务
2. 采用PHP7强类型（严格模式）
3. 支持更多的PSR规范
4. 对IDE更加友好
5. 统一和精简大量用法

## 开始 Clover 之旅

> 运行环境要求 PHP 7.1 +

 ```shell
composer create-project clovers/clover --prefer-dist
cd clover && php -S 127.0.0.1:8888 -t public
 ```
or
 ```shell
git clone https://github.com/donghaichen/clover.git
cd clover
composer intall
php -S 127.0.0.1:8888 -t public
 ```
### 目录结构

``` html
	├── app				应用目录
	│   ├── Controllers		控制器
	│   ├── helpers.php		帮助函数
	│   ├── Log.php			Log 类
	│   ├── Models			Model 类
	│   └── Mysql.php		Sql 类
	├── app.php			应用初始化
	├── cache			应用缓存
	├── composer.json		composer 定义
	├── composer.lock		锁定 composer 依赖
	├── config			配置目录
	│   ├── app.php			APP 配置
	│   ├── database.php		数据库配置
	│   ├── log.php			日志配置
	│   └── routes.php		路由配置
	├── public			WEB目录（对外访问目录）
	│   ├── favicon.ico		favicon
	│   ├── index.php		入口文件
	│   ├── robots.txt		robots 协议
	│   └── static			静态资源
	└── README.md			README
```
更多教程请移步 Clover 官方文档： [http://doc.mengniang.tv/clover](http://doc.mengniang.tv/clover)

## 添砖加瓦

登录 Github 或 Coding，fork 本项目，我们一起为 API 开发贡献更多艺术。

## License

The Clover framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
