<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:02
 */

namespace App\Http\Controllers\Backend;


use App\Enum\CodeEnum;
use App\Enum\CommonEnum;
use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TradeListPost;
use App\Http\Requests\Backend\UserEditPost;
use App\Http\Requests\Backend\WithdrawListPost;
use App\Services\Backend\TradeService;
use App\Services\Backend\WithdrawService;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    /**
     * 列表


     *
     * @param TradeListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(TradeListPost $post)
    {
        $totalRows = 0;
        $list = TradeService::singleton()->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->setAttribute( 'typeText', CommonEnum::FINANCE_TRADE_TYPE_TEXT_LIST[$item->type]);
        }

        return $this->jsonSuccess([
            'list' => $list,
            'page' => $post->page,
            'perPage' => $post->perPage,
            'totalRows' => $totalRows
        ]);
    }



}