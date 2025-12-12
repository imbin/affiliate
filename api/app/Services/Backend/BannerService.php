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
use App\Models\BannerModel;
use App\Repositories\Contracts\BannerRepositoryInterface;
use App\Services\BaseService;

class BannerService extends BaseService
{
    private $bannerRepository;
    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }
    public function createRow(BannerEditPost $post)
    {
        return $this->bannerRepository->createRow($post);
    }

    /**
     *
     * @param $id
     *
     * @return int
     */
    public function deleteRow($id)
    {
        return $this->bannerRepository->deleteRow($id);
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
        return $this->bannerRepository->findById( $id);
    }

    /**
     *
     * @param int $sku
     *
     * @return BannerModel
     */
    public function findBySku(int $sku)
    {
        return $this->bannerRepository->findBySku( $sku );
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