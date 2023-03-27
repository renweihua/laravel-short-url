# laravel-short-url

#### 介绍
基于`UrlHum`项目开发

#### 软件架构
- Laravel:8.75
- PHP:7.3^8.0


#### 安装教程

1. composer install
2. cp .env.example .env
3. 生成 APP_KEY：`php artisan key:generate`
4. 数据表迁移 `php artisan migrate`
5. 数据填充 `php artisan module:seed ShortUrl`
6. 创建`Storage`目录软链接 `php artisan storage:link`
