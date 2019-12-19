<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;


use App\Enum\CodeEnum;
use App\Enum\CommonEnum;
use App\Enum\OrderEnum;
use App\Events\NewOrderRecordEvent;
use App\Http\Requests\Backend\OrderCreatePost;
use App\Http\Requests\Backend\OrderEditPost;
use App\Http\Requests\Backend\OrderListPost;
use App\Http\Requests\BasePageListPost;
use App\Models\OrderGoodsModel;
use App\Models\OrderInfoModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService
{
    public function createRow(OrderCreatePost $post)
    {
        DB::beginTransaction();
        try {

            $order = new OrderInfoModel();
            $order->order_sn = $post->order_sn;
            $order->order_status = $post->order_status;
            $order->order_time = $post->order_time;
            $order->pay_time = $post->pay_time;
            $order->deliver_time = $post->deliver_time;
            $order->audit_days = OrderEnum::ORDER_AUDIT_DAYS;
            $order->user_id = $post->user_id;
            $order->commission_status = OrderEnum::COMMISSION_STATUS_PENDING;
            $order->order_amount = 0;

            if (!$order->save()) {
                throw new \Exception('写入订单失败', CodeEnum::BASE_SQL_ERROR);
            }

            $goodsList = [];
            foreach ( $post->goods_list as $goods ) {
                $goodsList[] = [
                    'sku' => $goods['sku'],
                    'sku_price' => $goods['sku_price'],
                    'sku_quantity' => $goods['sku_quantity'],
                    'order_sn' => $order->order_sn,
                    'order_id' => $order->id,
                ];
            }
            OrderGoodsModel::query()->insert( $goodsList );

            //在事件中计算订单佣金
            event(new NewOrderRecordEvent($order->id));

            DB::commit();

            return true;

        } catch ( \Throwable $throwable ) {
            DB::rollBack();
            Log::error( '订单录入失败:'.$throwable->getMessage());
            Log::error( $throwable->getTraceAsString());

            return false;
        }
    }

    public function editRow(OrderInfoModel $model, OrderEditPost $post)
    {
        $model->order_status = $post->order_status;
        $model->pay_time = $post->pay_time;
        $model->order_time = $post->order_time;
        $model->deliver_time = $post->deliver_time;

        return $model->save();
    }

    /**
     * 发放一笔订单佣金
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-26
     *
     * @param OrderInfoModel $model
     *
     * @return int
     */
    public function grantRow(OrderInfoModel $model)
    {
        DB::beginTransaction();
        try {
            $rows = OrderInfoModel::query()->where( 'id', $model->id )->where( 'commission_status', OrderEnum::COMMISSION_STATUS_PENDING )->update( [
                'commission_status' => OrderEnum::COMMISSION_STATUS_GRANTED
            ] );
            if ($rows == 0) {
                throw new \Exception('更改佣金状态，没有记录被更新', 1);
            }
            //增加用户一笔收入
            TradeService::singleton()->createRow( $model->user_id, $model->order_sn, $model->commission, CommonEnum::TRADE_TYPE_INCOME, '订单佣金');
            //增加余额
            UserService::singleton()->balanceAdd( $model->user_id, $model->commission);
            DB::commit();
            Log::info( '发放订单佣金', ['orderSn' => $model->order_sn, 'userId' => $model->user_id]);
            return true;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error( $throwable->getMessage());
            Log::error( $throwable->getTraceAsString());
            return false;
        }
    }
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
        $query = OrderInfoModel::query();
        if ($post->order_sn) {
            $query->where('order_sn', '=', $post->order_sn);
        }
        if ($post->status) $post->status = array_filter( $post->status);
        if (!empty($post->status) && count($post->status)) {
            $query->whereIn('order_status', $post->status);
        }

        $list = $query->forPage($post->page, $post->perPage)->orderByDesc( 'id')->get()->all();

        $totalRows = $query->toBase()->getCountForPagination();

        return $list;
    }
}