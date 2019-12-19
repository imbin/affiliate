<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Frontend;


use App\Http\Requests\OrderListPost;
use App\Models\OrderGoodsModel;
use App\Models\OrderInfoModel;
use App\Services\BaseService;

class OrderService extends BaseService
{

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     *
     * @return OrderInfoModel
     */
    public function findById(int $id)
    {
        return OrderInfoModel::singleton()->findById( $id);
    }

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param string $sn
     *
     * @return OrderInfoModel
     */
    public function findByOrderSn(string $sn)
    {
        return OrderInfoModel::singleton()->findBySn( $sn);
    }
    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int|array $id
     *
     * @return OrderGoodsModel[]
     */
    public function findGoodsListById($id)
    {
        return OrderGoodsModel::singleton()->findListByOrderId( $id);
    }
    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param OrderListPost $post
     * @param int $totalRows
     *
     * @return OrderInfoModel[]
     */
    public function findListByPage(OrderListPost $post, int &$totalRows)
    {
        $where = [];
        if ($post->order_sn) {
            $where[] = ['order_sn', '=', $post->order_sn];
        }
        if ($post->status) {
            $where[] = ['order_status', '=', $post->status];
        }
        $list = OrderInfoModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }
}