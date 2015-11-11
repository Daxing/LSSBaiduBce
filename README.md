## 在原sdk上添加了lss的直播会话接口
* 接口服务代码：Services/Live/LiveClient.php
* 接口使用封装：
  LiveSessionApp.php 封装接口应用，开发人员直接使用该文件。
  live_config.php    访问配置文件

## 使用方法
* 安装composer依赖
```
composer install
```
* 在live_config.php中配置你的ak和sk, bosBucket, userDomain 

* 打包sdk，生成Phar文件
```
php PharBuilder.php 
```
注：如果php的phar配置readonly，需要去修改php.ini：
```
phar.readonly = Off
```
* 运行测试，然后看看你的bce后台是否多了一个live session
```
php LiveSessionApp.php
```
注：正式使用时，请自行删除LiveSessionApp.php最后的厕所代码

