<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */

namespace App\Listeners;


use App\Enum\CodeEnum;
use App\Events\NewOrderRecordEvent;
use App\Services\Backend\BannerService;
use App\Services\Backend\OrderService;
use Illuminate\Support\Facades\Log;

/**
 * 新订单录入监听者
 * 1、新订单录入，要计算订单佣金
 * @package App\Listeners
 */
class NewOrderRecordListener
{
    private $orderService;
    private $bannerService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderService $orderService, BannerService $bannerService)
    {
        $this->orderService = $orderService;
        $this->bannerService = $bannerService;
    }

    /**
     * Handle the event.
     *
     * @param  NewOrderRecordEvent $event
     * @return void
     * @throws \Exception
     */
    public function handle(NewOrderRecordEvent $event)
    {
        $orderModel = $this->orderService->findById( $event->orderId );
        if (! $orderModel) {
            throw new \Exception('订单不存在, orderId='. $event->orderId, CodeEnum::BASE_SERVER_ERROR);
        }

        $orderGoodsList = $this->orderService->findGoodsListById( $event->orderId);
        foreach ($orderGoodsList as $goodsItem) {
            //小计
            $goodsItem->subtotal = bcmul($goodsItem->sku_price, $goodsItem->sku_quantity, 2);
            //默认 1%
            $goodsItem->sku_commission = bcmul(0.01, $goodsItem->subtotal, 2);
            $banner = $this->bannerService->findBySku( $goodsItem->sku );
            if ($banner) {
                //存在
                if ($banner->isReturnAmount()) {
                    //固定金额
                    $goodsItem->sku_commission = bcmul($banner->return_value, $goodsItem->sku_quantity, 2);
                } elseif ($banner->isReturnPercent()) {
                    //百分比
                    $goodsItem->sku_commission = bcmul(($banner->return_value / 100), $goodsItem->subtotal, 2);
                }
            }
            $goodsItem->save();
            $orderModel->commission = bcadd($orderModel->commission, $goodsItem->sku_commission, 2);
            $orderModel->order_amount = bcadd($orderModel->order_amount, $goodsItem->subtotal, 2);
        }
        $orderModel->save();
        Log::info( '订单佣金计算完成, orderSn:'.$orderModel->order_sn.', commission:'.$orderModel->commission );
    }
}