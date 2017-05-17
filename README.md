# easy_api-docs
一款配合Laravel框架使用的RESTAPI接口在线测试工具集.

# 开发原因
因为后台开发API接口一定是需要测试的,本人以前使用过swagger,但是...

> 1.swagger应该是遍历了所有controller文件,大型一些的项目开启一次要10s以上吧.

> 2.使用swagger一旦有某个地方的注释写错了,就gg,直接报错,让人无语...

为此,有了一个造轮子的想法....我自己写了一个简单版本的swagger,姑且叫easy_api-docs.

# 补充一下

1.我认为sawgger是一个非常棒的产品啊,没有贬低的意思.

2.不知道如何打包...所以只能麻烦需要用到的各位根据操作说明来慢慢配置了.

3.这次的造轮子更多的是一个对自己的锻炼吧~

# 操作说明

1. 在routes.php中新增一个路由 

```
// 新增一个api/routes
Route::get('/api-docs','ApiDocsController@apiData');
```

2. 把api-docs-json文件夹放在laravel的public目录下.
```
根据api-config.json来配置一些信息.

title-------项目名称
version-----项目版本
schemes-----API接口使用的版本协议(目前只支持http)
host--------域名/IP
basePath----基础路由 (建议写上'/api')

除api-config.json外,每一个.json文件表示一个模块.

例如:article.json 表示文章模块.

tag---------模块标记
name--------模块名称
apilist-----api列表

api列表是一个对象数组,其中每个对象的含义如下:

method------网络访问方式,目前只支持POST和GET(不区分大小,所以请统一大写)
path--------访问路径(这里以basePath:"/api" 为例子 实际网络上的访问路径是xxx.xxx.xxx/api/path)
summary-----该接口的简略介绍
description-该接口的详细描述
parameter---接口的参数,接收一个对象数组,其中每个对象含有2个属性{name , description}分别是名称和描述

```

