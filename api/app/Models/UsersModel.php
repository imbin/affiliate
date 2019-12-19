<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 23:51
 */

namespace App\Models;
//namespace App\Models;

use App\Enum\CommonEnum;
use App\Enum\UserEnum;
use App\Utils\UtilHelper;
use Illuminate\Database\Eloquent\Model;
use App\Models\ValidateBaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $user_name 用户名,可做登录
 * @property string $email Email,可做登录
 * @property string $mobile 手机号，可做登录
 * @property string $passwd 哈希后的密码
 * @property string $nick_name 昵称
 * @property int $gender 性别:0=未设置，1=男，2=女
 * @property string $birthday 出生年月
 * @property int $login_count
 * @property string $last_login_time 最近登录日期
 * @property int $status 状态:1=待审核、2=审核通过、3=驳回
 * @property string $track_code 用于追踪订单的追踪码
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 * @property string $is_disabled 是否禁用:0=否,1=是
 */
class UsersModel extends Authenticatable implements JWTSubject
{
    use FindListTrait;

    protected $table = 'users';
// false = 禁用Laravel时间戳字段
    public $timestamps = true;
//有create_time update_time 就恢复以下三行
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'ip' => UtilHelper::getIp(),
        ];
    }

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
     *
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param $userName
     *
     * @return $this
     */
    public function findByUserName($userName)
    {
        return static::query()->where(['user_name'=>$userName])->where(['is_disabled'=>CommonEnum::IS_DISABLED_NO])->orderByDesc( 'id')->first();
    }

    /**
     *
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param $mobile
     *
     * @return $this
     */
    public function findByMobile($mobile)
    {
        return static::query()->where(['mobile'=>$mobile])->where(['is_disabled'=>CommonEnum::IS_DISABLED_NO])->orderByDesc( 'id')->first();
    }

    /**
     *
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param $email
     *
     * @return $this
     */
    public function findByEmail($email)
    {
        return static::query()->where(['email'=>$email])->where(['is_disabled'=>CommonEnum::IS_DISABLED_NO])->orderByDesc( 'id')->first();
    }
    /**
     * Author: zhaobin
     * Date: 2019-11-16
     * @return int
     */
    public function incrementLoginCount()
    {
        return $this->increment('login_count', 1, ['last_login_time' => date('Y-m-d H:i:s')]);
    }

    /**
     * 是否通过审核
     * Author: zhaobin
     * Date: 2019-11-07
     * @return bool
     */
    public function isPassed()
    {
        return $this->status === UserEnum::STATUS_PASS;
    }

    /**
     * 是否待审核
     * Author: zhaobin
     * Date: 2019-11-07
     * @return bool
     */
    public function isPending()
    {
        return $this->status === UserEnum::STATUS_PENDING;
    }

}
