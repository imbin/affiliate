<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-06
 * Time: 23:49
 */

namespace App\Models;

use App\Utils\UtilHelper;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This is the model class for table "admin_user".
 *
 * @property int $id
 * @property string $user_name 登录名
 * @property string $passwd 密码
 * @property string $real_name 姓名
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 */
class AdminUserModel extends Authenticatable implements JWTSubject
{
    use FindListTrait;

    protected $table = 'admin_user';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则
     * Author: xxx@xx.com
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
     *
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-07
     *
     * @param $userName
     *
     * @return $this
     */
    public function findByUserName(string $userName)
    {
        return static::query()->where(['user_name'=>$userName])->orderByDesc( 'id')->first();
    }

}
