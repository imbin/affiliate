<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;


use App\Http\Requests\Backend\BannerEditPost;
use App\Http\Requests\Backend\BannerListPost;
use App\Http\Requests\BasePageListPost;
use App\Models\BannerModel;
use App\Services\BaseService;

class BannerService extends BaseService
{
    public function createRow(BannerEditPost $post)
    {
        $model = new BannerModel();
        $model->title = $post->title;
        $model->status = $post->status;
        $model->redirect_url = $post->redirect_url;
        $model->pic_url = $post->pic_url;
        $model->sku = $post->sku;
        $model->price = $post->price;
        $model->return_type = $post->return_type;
        $model->return_value = $post->return_value;
        return $model->save();
    }

/**
     *
     * @param $id
     *
     * @return int
     */
    public function deleteRow($id)
    {
        return BannerModel::query()->where('id', $id)->delete();
    }
    public function editRow(BannerModel $model, BannerEditPost $post)
    {
        $model->title = $post->title;
        $model->sku = $post->sku;
        $model->price = $post->price;
        $model->return_type = $post->return_type;
        $model->return_value = $post->return_value;
        $model->status = $post->status;
        $model->redirect_url = $post->redirect_url;
        $model->pic_url = $post->pic_url;

        return $model->save();
    }
/**
     *
     * @param int $id
     *
     * @return BannerModel
     */
    public function findById(int $id)
    {
        return BannerModel::singleton()->findById( $id);
    }

/**
     *
     * @param int $sku
     *
     * @return BannerModel
     */
    public function findBySku(int $sku)
    {
        return BannerModel::singleton()->findBySku( $sku);
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