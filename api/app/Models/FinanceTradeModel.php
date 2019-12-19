<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */
namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * This is the model class for table "finance_trade".
 *
 * @property int $id
 * @property int $user_id 所属用户
 * @property string $business_sn 收支相关单号,如订单号,提现号
 * @property string $amount 操作金额
 * @property int $type 收支类型：1=收入、2=提现
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 * @property string $remark 备注
 */
class FinanceTradeModel extends ValidateBaseModel
{

    protected $table = 'finance_trade';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则
     * Author: xxx
     * Date: 2019-02-25
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
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param $sn
     *
     * @return $this
     */
    public function findBySn(string $sn)
    {
        return static::query()->where('business_sn', '=', $sn)->first();
    }
}
