<p align="center"><img src="https://res.cloudinary.com/clover-cms/image/upload/v1567957620/clover_pljwc5.png" height="50"></p>

[![Build Status](https://travis-ci.org/donghaichen/clover.svg?branch=master)](https://travis-ci.org/donghaichen/clover)
[![Total Downloads](https://poser.pugx.org/clovers/clover/downloads)](https://packagist.org/packages/clovers/clover)
[![Latest Stable Version](https://poser.pugx.org/clovers/clover/v/stable)](https://packagist.org/packages/clovers/clover)
[![Latest Unstable Version](https://poser.pugx.org/clovers/clover/v/unstable)](https://packagist.org/packages/clovers/clover)
[![composer.lock](https://poser.pugx.org/clovers/clover/composerlock)](https://packagist.org/packages/clovers/clover)
[![License](https://poser.pugx.org/clovers/clover/license)](https://packagist.org/packages/clovers/clover)

## 认识 Clover

	 为速度而生的 PHP 框架
	
	 专注于 API 开发

#### 特性
1. 系统响应更快
2. 代码精简
3. 采用PHP7强类型（严格模式）
4. 支持更多的PSR规范
5. 对IDE更加友好

## 开始 Clover 之旅

> 运行环境要求 PHP 7.1 +

 ```shell
 # 安装最新稳定版
composer create-project clovers/clover --prefer-dist
# 安装最新开发版
composer create-project clovers/clover=dev-master --prefer-dist
# 运行项目
cd clover && composer start
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

## 贡献代码

登录 Github 或 Coding，fork 本项目，我们一起为 API 开发贡献更多艺术。

## License

The Clover framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
