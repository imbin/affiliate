<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * This is the model class for table "user_balance".
 *
 * @property int $id
 * @property int $user_id 所属用户
 * @property float $balance 余额（可提现金额）
 * @property float $frozen 冻结金额（提现中）
 * @property float $withdraw 已提现金额
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 */
class UserBalanceModel extends ValidateBaseModel
{
    use FindListTrait;

    protected $table = 'user_balance';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则
     * Author: xxx     *
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
     * @param int $userId
     *
     * @return bool
     */
    public function createRow(int $userId)
    {
        return static::query()->insert(['user_id' => $userId]);
    }

    /**
     *
     * @param int $userId
     *
     * @return $this
     */
    public function findBalance(int $userId)
    {
        return static::query()->firstOrCreate([['user_id', '=', $userId]]);
    }

}
