<?php

namespace App\Repositories;

use App\Http\Requests\Backend\BannerEditPost;
use App\Http\Requests\Backend\BannerListPost;
use App\Models\BannerModel;
use App\Repositories\Contracts\BannerRepositoryInterface;

class BannerRepository implements BannerRepositoryInterface
{
    protected $model;

    public function __construct(BannerModel $model)
    {
        $this->model = $model;
    }
    public function deleteRow($id)
    {
        return BannerModel::query()->where('id', $id)->delete();
    }

    /**
     * @param BannerEditPost $post
     * @return bool
     */
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
     * @param int $id
     * @return BannerModel|null
     */
    public function findById(int $id)
    {
        return $this->model->findById($id);
    }

    /**
     * @param int $sku
     *
     * @return BannerModel
     */
    public function findBySku(int $sku)
    {
        return $this->model->findBySku( $sku);
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