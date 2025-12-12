<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;

use App\Http\Requests\Backend\TradeListPost;
use App\Models\FinanceWithdrawModel;
use App\Models\FinanceTradeModel;
use App\Models\UserBalanceModel;
use App\Models\UsersModel;
use App\Services\BaseService;
use function PHPUnit\Framework\StaticAnalysis\HappyPath\AssertIsArray\consume;

class TradeService extends BaseService
{

    /**
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
     *
     * @param string $sn
     *
     * @return FinanceTradeModel
     */
    public function findBySn(string $sn)
    {
        return FinanceTradeModel::singleton()->findBySn( $sn);
    }

    /**
     *
     * @param int $userId
     * @param string $sn
     * @param float $amount
     * @param int $type
     * @param string $remark
     * @return bool
     */
    public function createRow(int $userId, string $sn, float $amount, int $type, string $remark)
    {
        $model = new FinanceTradeModel();
        $model->user_id = $userId;
        $model->business_sn = $sn;
        $model->amount = $amount;
        $model->type = $type;
        $model->remark = $remark;
        return $model->save();
    }

    /**
     *
     * @param TradeListPost $post
     * @param int $totalRows
     *
     * @return FinanceTradeModel[]
     */
    public function findListByPage(TradeListPost $post, int &$totalRows)
    {
        $where = [];
        if ($post->user_id) {
            $where[] = ['ft.user_id', '=', $post->user_id];
        }
        if ($post->sn) {
            $where[] = ['ft.business_sn', '=', $post->sn];
        }
        if ($post->type) {
            $where[] = ['ft.type', '=', $post->type];
        }
        $query = FinanceTradeModel::query()->from(FinanceTradeModel::singleton()->getTable().' as ft');
        $query->leftjoin(UsersModel::singleton()->getTable().' as u', 'u.id', '=', 'ft.user_id');
        $query->select('ft.*', 'u.user_name');
        $list = $query->where($where)->forPage( $post->page, $post->perPage)->orderByDesc( 'ft.id')->get()->all();

        $totalRows = $query->toBase()->getCountForPagination();

        return $list;
    }
}