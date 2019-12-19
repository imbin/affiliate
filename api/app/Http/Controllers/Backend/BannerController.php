<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:02
 */

namespace App\Http\Controllers\Backend;


use App\Enum\BannerEnum;
use App\Enum\CodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BannerEditPost;
use App\Http\Requests\Backend\BannerListPost;
use App\Services\Backend\BannerService;
use App\Utils\UtilHelper;

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
    /**
     * 创建
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param BannerEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionCreate(BannerEditPost $post)
    {
        $exist = BannerService::singleton()->findBySku( $post->sku);
        if ($exist) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, 'sku已经存在, sku:'. $post->sku);
        }
        $ret = BannerService::singleton()->createRow($post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 修改
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     * @param BannerEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEdit(int $id, BannerEditPost $post)
    {
        if (0 >= $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $model = BannerService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $ret = BannerService::singleton()->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 删除
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionDelete(int $id)
    {
        if ($id < 1) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '参数错误');
        }
        $model = BannerService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = BannerService::singleton()->deleteRow($id);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }


}