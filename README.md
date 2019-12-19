# 广告联盟系统

这是我对于PHP开发多年的一个最佳实践的演示系统。
前端采用VueJS 2.6, 后端Laravel 6.2，基于MySQL 5.7存储。
没有做的太复杂，在于演示前后端分离系统的开发，展示了VueJS+PHP(Laravel)+MySQL的操作流程，是一个完整能运行的系统。
拥有联盟客模块、订单模块、素材模块。
涉及RabbitMQ消息中间件的部分不好演示，就没有放进来。

# 目录说明

```
修改 /etc/hosts
127.0.0.1 www.dev.com api.dev.com admin.dev.com

api       基于Laravel 6.2的Api服务器，演示部署域名: api.dev.com
frontend  给联盟客用的主站界面，演示部署域名: www.dev.com
backend   后台管理界面，演示部署域名: admin.dev.com

```

# 最佳实践

- 本系统的意义在于演示PHP Laravel最佳实践

## 使用Controller做为入口接收前端提交参数

- app/Http/Controllers/Backend/BannerController.php
- 在Controller中敲 $item->pic_url 都是带IDE智能提示的

```php
use App\Http\Requests\Backend\BannerListPost;
class BannerController extends Controller
{
    /**
     * 列表
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param BannerListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(BannerListPost $post)
    {
        $totalRows = 0;
        $list = BannerService::singleton()->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->thumb_url = UtilHelper::thumbUrl( $item->pic_url);
            $item->statusText = BannerEnum::STATUS_TEXT_LIST[$item->status] ?? '';
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
 * @property $title string
 * @property $status int
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

## 演示Service操作MySQL

- 在Service中敲$post->title都是带IDE智能提示的
- 在Service中标记@return BannerModel[]，可在调用处使用IDE智能提示Model字段名

```php
class BannerService extends BaseService
{
    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
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
        $list = BannerModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

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
