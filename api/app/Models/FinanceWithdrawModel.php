<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * This is the model class for table "finance_withdraw".
 *
 * @property int $id
 * @property string $sn 提现号
 * @property int $user_id 所属用户
 * @property float $amount 提现金额
 * @property int $status 提现状态:1=未审核、2=驳回、3=提现成功
 * @property int $way 提现方式：1=银行卡，2=支付宝
 * @property string $card 银行卡号或支付宝账号
 * @property string $name 收款人姓名
 * @property string $remark 备注
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 */
class FinanceWithdrawModel extends ValidateBaseModel
{
    use FindListTrait;

    protected $table = 'finance_withdraw';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则


     *
     * 'title' => 'required|max:255',
     * @return array
     */
    public function getRules()
    {
        return [
            //'merchant_id' => 'required|integer',
            //'title' => 'required|max:255',
        ];
    }

/**
     *
     * @param string $sn
     *
     * @return $this
     */
    public function findBySn(string $sn)
    {
        return static::query()->where('sn', '=', $sn)->first();
    }
}
