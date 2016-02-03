Theseus 1.0.0 beta
=======================================


Theseus专注提供无状态的 API 服务

 基于命名空间和众多PHP新特性
 API支持完善
 命令行访问支持

Theseus的运行环境要求

 + PHP >= 5.5.9
 + OpenSSL 扩展
 + PDO PHP 扩展


## 参与开发
注册并登录 Github或Coding帐号， fork 本项目来改变这个混乱的API世界。

# Usage

### 安装

请使用 composer 安装

```shell
git clone https://github.com/donghaichen/theseus.git
cd theseus
composer install
php -S 0.0.0.0:8080 -t public public/index.php
```

优雅链接

```nginx

location / {
          try_files $uri $uri/ /index.php?$query_string;
      }
```


_更多教程和使用请移步 Theseus 官方文档：http://doc.mengniang.tv/


# License

MIT