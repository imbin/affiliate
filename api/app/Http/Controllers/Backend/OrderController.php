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
use App\Enum\OrderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderCreatePost;
use App\Http\Requests\Backend\OrderEditPost;
use App\Http\Requests\Backend\OrderListPost;
use App\Services\Backend\OrderService;
use App\Services\Backend\UserService;
use App\Utils\UtilHelper;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * 列表
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param OrderListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(OrderListPost $post)
    {
        $totalRows = 0;
        $list = OrderService::singleton()->findListByPage($post, $totalRows);

        if (count($list)) {
            $idList = array_column($list, 'id');
            $tmpList = OrderService::singleton()->findGoodsListById( $idList);
            $orderGoodsList = [];
            foreach ($tmpList as $goods) {
                $orderGoodsList[$goods->order_id][] = $goods;
            }
            $idList = array_unique( array_column($list, 'user_id'));
            $tmpList = UserService::singleton()->findListById( $idList);
            $orderUserList = array_column($tmpList, null, 'id');
            Log::info('goodsList2',  $orderGoodsList);
            foreach ( $list as $item ) {
                if (isset( $orderGoodsList[$item->id])) {
                    $item->setAttribute( 'goods_list', $orderGoodsList[ $item->id ] );
                } else {
                    $item->setAttribute( 'goods_list', [] );
                }
                if (isset( $orderUserList[$item->user_id])) {
                    $item->setAttribute( 'userInfo', $orderUserList[ $item->user_id ] );
                } else {
                    $item->setAttribute( 'userInfo', null );
                }
                $item->canGrantTime = ($item->pay_time || $item->deliver_time) ? date('Y-m-d H:i:s', strtotime($item->pay_time) + ($item->audit_days * 86400)) : '';
                $item->statusText = OrderEnum::STATUS_TEXT_LIST[ $item->order_status ] ?? '';
                $item->commissionStatusText = OrderEnum::COMMISSION_STATUS_TEXT_LIST[ $item->commission_status ] ?? '';
            }
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
     * @param OrderCreatePost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionCreate(OrderCreatePost $post)
    {
        $exists = OrderService::singleton()->findByOrderSn( $post->order_sn );
        if ($exists) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, '订单号已经存在:'.$post->order_sn);
        }
        $user = UserService::singleton()->findById( $post->user_id );
        if (! $user) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, '用户不存在:'.$post->user_id);
        }
        $ret = OrderService::singleton()->createRow($post);
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
     * @param OrderEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEdit(int $id, OrderEditPost $post)
    {
        if (0 >= $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $model = OrderService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        if ($model->commission_status == OrderEnum::COMMISSION_STATUS_GRANTED) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '订单已发佣金，不能再更改订单信息');
        }
        $ret = OrderService::singleton()->editRow($model, $post);
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionGrant(int $id)
    {
        if (0 >= $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $model = OrderService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $ret = OrderService::singleton()->grantRow( $model);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }
}