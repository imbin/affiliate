<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Frontend;

use App\Enum\BannerEnum;
use App\Http\Requests\BasePageListPost;
use App\Models\BannerModel;
use App\Services\BaseService;

class BannerService extends BaseService
{

    /**
     * Author: zhaobin
     * Date: 2019-11-14
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
     * Author: zhaobin
     * Date: 2019-11-13
     *
     * @param BasePageListPost $post
     * @param int $totalRows
     *
     * @return BannerModel[]
     */
    public function findListByPage(BasePageListPost $post, int &$totalRows)
    {
        $where = [
            ['status', '=', BannerEnum::STATUS_ON]
        ];
        if ($post->get('title')) {
            $where[] = ['title', 'like', '%'.addcslashes( $post->get('title'), '%').'%'];
        }
//        if ($post->status) {
//            $where[] = ['status', '=', $post->status];
//        }
        $list = BannerModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }
}