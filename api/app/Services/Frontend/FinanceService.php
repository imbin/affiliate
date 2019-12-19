<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Frontend;


use App\Enum\CommonEnum;
use App\Http\Requests\BasePageListPost;
use App\Http\Requests\OrderListPost;
use App\Http\Requests\WithdrawCreatePost;
use App\Models\FinanceWithdrawModel;
use App\Models\OrderGoodsModel;
use App\Models\FinanceTradeModel;
use App\Models\UserBalanceModel;
use App\Services\BaseService;
use App\Utils\UtilHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceService extends BaseService
{

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     *
     * @return FinanceTradeModel
     */
    public function findById(int $id)
    {
        return FinanceTradeModel::singleton()->findById( $id);
    }

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param string $sn
     *
     * @return FinanceTradeModel
     */
    public function findTradeBySn(string $sn)
    {
        return FinanceTradeModel::singleton()->findBySn( $sn);
    }
    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param int $userId
     * @param BasePageListPost $post
     * @param int $totalRows
     *
     * @return FinanceTradeModel[]
     */
    public function findTradeListByPage(int $userId, BasePageListPost $post, int &$totalRows)
    {
        $where = [];
        $where[] = ['user_id', '=', $userId];
        $list = FinanceTradeModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-25
     *
     * @param int $userId
     *
     * @return UserBalanceModel
     */
    public function findBalance(int $userId)
    {
        return UserBalanceModel::singleton()->findBalance( $userId);
    }

    /**
     * 提现列表
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-25
     *
     * @param int $userId
     * @param BasePageListPost $post
     * @param $totalRows
     *
     * @return FinanceWithdrawModel[]
     */
    public function findWithdrawListByUser(int $userId, BasePageListPost $post, &$totalRows)
    {
        $where = [
            ['user_id', '=', $userId]
        ];

        return FinanceWithdrawModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);
    }

    /**
     * 创建一个提现
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-25
     *
     * @param int $userId
     * @param WithdrawCreatePost $post
     *
     * @return bool
     */
    public function createWithdraw(int $userId, WithdrawCreatePost $post)
    {
        DB::beginTransaction();
        try {
            $model = new FinanceWithdrawModel();
            $model->sn = UtilHelper::generateSn(CommonEnum::WITHDRAW_SN_PREFIX, 20);
            $model->amount = $post->amount;
            $model->user_id = $userId;
            $model->way = $post->way;
            $model->status = CommonEnum::WITHDRAW_STATUS_PENDING;
            $model->card = $post->card;
            $model->name = $post->name;
            $model->save();

            //冻结余额
            $query = sprintf('UPDATE %s SET frozen=frozen+? WHERE user_id=? AND (balance - withdraw) >= ?', UserBalanceModel::singleton()->getTable());
            DB::update( $query, [
                $post->amount, $userId, $post->amount
            ]);

            DB::commit();
            Log::info( '创建提现', ['userId' => $userId, 'post' => $post->toArray()]);

            return true;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error( $throwable->getMessage() .PHP_EOL . $throwable->getTraceAsString());
            return false;
        }
    }

}