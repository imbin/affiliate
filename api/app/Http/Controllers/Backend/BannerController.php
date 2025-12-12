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
    /**
     * 创建
     *
     * @param BannerEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionCreate(BannerEditPost $post)
    {
        $exist = $this->bannerService->findBySku( $post->sku);
        if ($exist) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, 'sku已经存在, sku:'. $post->sku);
        }
        $ret = $this->bannerService->createRow($post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 修改
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
        $model = $this->bannerService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $ret = $this->bannerService->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 删除
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
        $model = $this->bannerService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = $this->bannerService->deleteRow($id);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }


}