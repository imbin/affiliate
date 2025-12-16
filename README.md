# 广告联盟系统 & Laravel最佳实践Demo

- 这是我对于PHP开发多年的一个Laravel最佳实践的演示系统
- 前端采用VueJS 2.6 + ElementUI, 后端采用PHP Laravel 6.2，基于MySQL 5.7存储。
- 没有做的太复杂，在于演示前后端分离系统的开发，展示了VueJS+PHP(Laravel)+MySQL的操作流程，是一个完整能运行的系统。
- 拥有联盟客模块、订单模块、素材模块。
- 涉及RabbitMQ消息中间件的部分不好演示，就没有放进来。

# 目录说明

```
api       基于Laravel 6.2的Api服务器，演示部署域名: api.dev.com
frontend  给联盟客用的主站界面，演示部署域名: www.dev.com
backend   后台管理界面，演示部署域名: admin.dev.com


nodejs v12.22.12

```

# 部署说明

- 推荐使用 https://github.com/imbin/docker-compose
- 一键部署LNMP环境

## 初始化SQL

### 方式一：Migrate

```shell
cd backend
php artisan migrate
```

### 方式二：SQL导入

- api/databasee/aff.sql

## 初始账号

- 后台/密码: admin / admin
- 前台/密码: test / test123

## Nginx Vhost

- nginx.conf

```
load_module "modules/ngx_http_image_filter_module.so";
```

- conf/sites-enabled/aff-dev.com.conf

```

server
{
    listen 80;
    server_name admin.dev.com;
    index index.php index.html;
    root  /data/github-affiliate/backend/dist;
    underscores_in_headers off;
    ignore_invalid_headers off;

    location ~ ^/(api)/ {
        alias  /data/github-affiliate/api/public/;
        try_files $uri $uri/ /index.php$is_args$args;
        #?$query_string
    }

    location ~* ^/thumb {
        root /data/github-affiliate/api/storage/app/public;#初始加载路径
        set $width 100;
        set $height 100;
        set $quality 80;
        set $dimens "";

        if ($uri ~* "^/thumb_(\d+)x(\d+)_(\d+)/(.*)" ) {
            set $width $1;
            set $height $2;
            set $quality $3;
            set $image_path $4;
            set $dimens "_$1x$2_$3";
        }

        if ($uri ~* "^/thumb/(.*)" ) {
            set $image_path $1;
        }

        if (!-f $request_filename) {
            set $image_uri image_resize/$image_path?width=$width&height=$height&quality=$quality;
            proxy_pass http://127.0.0.1:8082/$image_uri;
            break;
        }
        proxy_store on;
        proxy_temp_path /data/github-affiliate/api/storage/app/public;#缓存路径
        proxy_store_access user:rw group:rw all:r;
        proxy_set_header Host $host;
        access_log off;
    }

    location /storage {
        alias  /data/github-affiliate/api/storage/app/public;
        try_files $uri $uri/ =404;
    }
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    #error_page   404   /404.html;


    location ~ \.php$ {
        include fastcgi_params;
        #fastcgi_pass php:9000;

        # 56
        #fastcgi_pass phpfpm_56_backend;

        # 71
        #fastcgi_pass phpfpm_72_backend;
        # 7-latest
        fastcgi_pass phpfpm_72_backend;

        fastcgi_index index.php;
        # fastcgi_param SCRIPT_FILENAME $request_filename;
        fastcgi_param SCRIPT_FILENAME /data/github-affiliate/api/public$fastcgi_script_name;
        fastcgi_param APP_ENV local;
    }

    error_log /data/docker-compose/nginx/logs/edu_error.log;
    access_log /data/docker-compose/nginx/logs/edu_access.log;
    # access_log off;
}
# dynamic thumb image server
server
{
    listen 8082;
    server_name admin.dev.com;
    index index.php index.html;
    root  /data/github-affiliate/api/storage/app/public;
    underscores_in_headers off;
    ignore_invalid_headers off;

    location /image_resize {
        alias /data/github-affiliate/api/storage/app/public;
        image_filter crop $arg_width $arg_height;
        image_filter_jpeg_quality $arg_quality;
        access_log off;
    }
    location / {
        try_files $uri $uri/ =404;
    }

    #error_page   404   /404.html;



    error_log /data/docker-compose/nginx/logs/edu_error.log;
    access_log off;
    #access_log /data/docker-compose/nginx/logs/edu_access.log;
}
```

## Hosts

- 修改 /etc/hosts
- 127.0.0.1 www.dev.com api.dev.com admin.dev.com


# 最佳实践

- 本系统的意义在于演示PHP Laravel最佳实践
- Controller和Service/Repository充分利用现代框架的依赖倒置原则, 在构造函数中创建依赖的下游分层, 放弃singleton的静态单例模式
- 因业务操作比较简单，没有复杂的Service间调用，Repository分层只用了Banner模块演示

## 使用Controller做为入口接收前端提交参数

- app/Http/Controllers/Backend/BannerController.php
- 在Controller中敲 $item->pic_url 在PhpStorm中都是带IDE智能提示的

```php
use App\Http\Requests\Backend\BannerListPost;
class BannerController extends Controller
{
    private $bannerService;
    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }
    /**
     * 列表
     *
     * @param BannerListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(BannerListPost $post)
    {
        $totalRows = 0;
        $list = $this->bannerService->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->thumb_url = UtilHelper::thumbUrl( $item->pic_url);
            $item->status_text = BannerEnum::STATUS_TEXT_LIST[$item->status] ?? '';
        }

        return $this->jsonSuccess([
            'list' => $list,
            'page' => $post->page,
            'perPage' => $post->perPage,
            'totalRows' => $totalRows
        ]);
    }
}
```
- app/Http/Requests/Backend/BannerListPost.php

```php
/**
 * 使用Laravel参数验证器
 * Class BannerListPost
 * @package App\Http\Requests
 *
 * @property $title string 搜索关键词
 * @property $status int 状态过滤
 */
class BannerListPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'nullable|integer|in:1,2',
            'title' => 'nullable|min:1|max:50',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'status' => '上架状态',
        ];
    }
}
```

## 演示Service操作MySQL, Repository模式演示

- 在Repository中敲$post->title都是带IDE智能提示的
- 在Service中标记@return BannerModel[]，可在调用处使用IDE智能提示Model字段名

```php
class BannerService extends BaseService
{
    private $bannerRepository;
    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }
    /**
     *
     * @param BannerListPost $post
     * @param int $totalRows
     *
     * @return BannerModel[]
     */
    public function findListByPage(BannerListPost $post, int &$totalRows)
    {
        return $this->bannerRepository->findListByPage( $post, $totalRows);
    }
}
```

## 演示Repository操作Model
```php

class BannerRepository implements BannerRepositoryInterface
{
    protected $model;

    public function __construct(BannerModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * @param BannerListPost $post
     * @param int $totalRows
     *
     * @return BannerModel[]
     */
    public function findListByPage(BannerListPost $post, int &$totalRows)
    {
        $where = [];
        if ($post->title) {
            $where[] = ['title', 'like', '%'.addcslashes( $post->title, '%').'%'];
        }
        if ($post->status) {
            $where[] = ['status', '=', $post->status];
        }
        $list = $this->model->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }
}
```

## 演示Model操作MySQL

```php
class BannerModel extends ValidateBaseModel
{
    /**
     * 分页获取多个实例（Model数组）
     * @author: tobinzhao@gmail.com
     * Date: 2019-03-02    *
     *
     * @param array $where
     * @param int $page
     * @param int $perPage
     * @param int $totalRows 返回查询总数
     *
     * @return static[]
     */
    public function findListByPage(array $where, $page, $perPage, &$totalRows, $orderByDesc = 'id' )
    {
        $query = static::query();
        $query->where($where)->forPage( $page, $perPage)->orderByDesc($orderByDesc);

        $list = $query->get()->all();
        $totalRows = $query->toBase()->getCountForPagination();
        return $list;
    }
}
```

## 系统运行效果图

 ![image](https://github.com/imbin/affiliate/raw/master/screenshots/homepage.png)
 ![image](https://github.com/imbin/affiliate/raw/master/screenshots/banners.png)
 ![image](https://github.com/imbin/affiliate/raw/master/screenshots/admin-banners.png)
 ![image](https://github.com/imbin/affiliate/raw/master/screenshots/admin-order-create.png)
