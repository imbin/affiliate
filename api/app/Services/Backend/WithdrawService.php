<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;


use App\Enum\CommonEnum;
use App\Http\Requests\Backend\WithdrawListPost;
use App\Http\Requests\BasePageListPost;
use App\Http\Requests\WithdrawCreatePost;
use App\Models\FinanceTradeModel;
use App\Models\FinanceWithdrawModel;
use App\Models\UserBalanceModel;
use App\Services\BaseService;
use App\Services\Frontend\FinanceService;
use App\Utils\UtilHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawService extends BaseService
{

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     *
     * @return FinanceWithdrawModel
     */
    public function findById(int $id)
    {
        return FinanceWithdrawModel::singleton()->findById( $id);
    }

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param string $sn
     *
     * @return FinanceWithdrawModel
     */
    public function findBySn(string $sn)
    {
        return FinanceWithdrawModel::singleton()->findBySn( $sn);
    }
    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param WithdrawListPost $post
     * @param int $totalRows
     *
     * @return FinanceWithdrawModel[]
     */
    public function findListByPage(WithdrawListPost $post, int &$totalRows)
    {
        $where = [];
        if ($post->sn) {
            $where[] = [ 'sn', '=', $post->sn ];
        }
        if ($post->status) {
            $where[] = [ 'status', '=', $post->status ];
        }
        $list = FinanceWithdrawModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param $id
     *
     * @return int
     */
    public function updateStatusRow(FinanceWithdrawModel $model, int $status, string $remark = '')
    {
        DB::beginTransaction();
        try {
            $model->status = $status;
            $model->remark = $remark;
            $model->save();

            if ($status == CommonEnum::WITHDRAW_STATUS_COMPLETE) {
                //已提现金额加数
                $sql = sprintf( 'UPDATE %s SET frozen = frozen - ?, withdraw = withdraw + ? where user_id = ?', UserBalanceModel::singleton()->getTable() );
                DB::update( $sql, [
                    $model->amount,
                    $model->amount,
                    $model->user_id
                ] );
                //记录支出
                TradeService::singleton()->createRow( $model->user_id, $model->sn, $model->amount, CommonEnum::TRADE_TYPE_EXPEND, '余额提现' );
            }
            DB::commit();
            Log::info('审核提现单', ['sn' => $model->sn, 'status' => $status, 'remark' => $remark]);
            return true;
        } catch (\Throwable $throwable) {

        }
    }

}