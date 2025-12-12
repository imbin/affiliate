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
    protected $orderService;
    protected $userService;
    public function __construct(OrderService $orderService, UserService $userService) {
        $this->orderService = $orderService;
        $this->userService = $userService;
    }
    /**
     * 列表
     *
     * @param OrderListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(OrderListPost $post)
    {
        $totalRows = 0;
        $list = $this->orderService->findListByPage($post, $totalRows);

        if (count($list)) {
            $idList = array_column($list, 'id');
            $tmpList = $this->orderService->findGoodsListById( $idList);
            $orderGoodsList = [];
            foreach ($tmpList as $goods) {
                $orderGoodsList[$goods->order_id][] = $goods;
            }
            $idList = array_unique( array_column($list, 'user_id'));
            $tmpList = $this->userService->findListById( $idList);
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
     *
     * @param OrderCreatePost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionCreate(OrderCreatePost $post)
    {
        $exists = $this->orderService->findByOrderSn( $post->order_sn );
        if ($exists) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, '订单号已经存在:'.$post->order_sn);
        }
        $user = $this->userService->findById( $post->user_id );
        if (! $user) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, '用户不存在:'.$post->user_id);
        }
        $ret = $this->orderService->createRow($post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 修改
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
        $model = $this->orderService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        if ($model->commission_status == OrderEnum::COMMISSION_STATUS_GRANTED) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '订单已发佣金，不能再更改订单信息');
        }
        $ret = $this->orderService->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 修改
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
        $model = $this->orderService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '对象不存在');
        }
        $ret = $this->orderService->grantRow( $model);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }
}