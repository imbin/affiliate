<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;


use App\Enum\CommonEnum;
use App\Enum\UserEnum;
use App\Http\Requests\Backend\UserEditPost;
use App\Http\Requests\Backend\UserListPost;
use App\Http\Requests\BasePageListPost;
use App\Http\Requests\EditPasswordPost;
use App\Http\Requests\EditProfilePost;
use App\Http\Requests\RegisterPost;
use App\Models\UserBalanceModel;
use App\Models\UsersModel;
use App\Services\BaseService;
use App\Utils\UtilHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService extends BaseService
{

    /**
     *
     * @param $id
     *
     * @return int
     */
    public function disableOrEnableRow(UsersModel $model, int $val)
    {
        $model->is_disabled = $val;
        return $model->save();
    }

    /**
     *
     * @param $id
     *
     * @return int
     */
    public function updateStatusRow(UsersModel $model, int $status)
    {
        $model->status = $status;
        return $model->save();
    }

    public function editRow(UsersModel $model, UserEditPost $post)
    {
        $model->passwd = UtilHelper::hashPassword( $post->password);

        return $model->save();
    }
    /**
     *
     * @param int $id
     *
     * @return UsersModel
     */
    public function findById(int $id)
    {
        return UsersModel::singleton()->findById( $id);
    }

    /**
     *
     * @param array $id
     *
     * @return UsersModel[]
     */
    public function findListById(array $id)
    {
        return UsersModel::singleton()->findListById( $id);
    }
    /**
     *
     * @param UserListPost $post
     * @param int $totalRows
     *
     * @return UsersModel[]
     */
    public function findListByPage(UserListPost $post, int &$totalRows)
    {
        $where = [];
        if ($post->id) {
            $where[] = ['id', '=', $post->id];
        }
        if ($post->userName) {
            $where[] = ['user_name', 'like', '%'.addcslashes( $post->userName, '%').'%'];
        }
        if ($post->nickName) {
            $where[] = ['nick_name', 'like', '%'.addcslashes( $post->nickName, '%').'%'];
        }
        if ($post->gender) {
            $where[] = ['gender', '=', $post->gender];
        }
        $list = UsersModel::singleton()->findListByPage( $where, $post->page, $post->perPage, $totalRows);

        return $list;
    }

    /**
     * @param $mobile string
     * @return UsersModel|null
     */
    public function findByMobile($mobile)
    {
        return UsersModel::singleton()->findByMobile( $mobile );
    }

    /**
     * @param $email string
     * @return UsersModel|null
     */
    public function findByEmail($email)
    {
        $isEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($isEmail) {
            return UsersModel::singleton()->findByEmail( $email);
        }
        return null;
    }

    /**
     * @param $userName string
     * @return UsersModel|null
     */
    public function findByUserName($userName)
    {
        return UsersModel::singleton()->findByUserName( $userName);
    }


    public function editPassword(EditPasswordPost $post, UsersModel $model)
    {
        $model->passwd = UtilHelper::hashPassword( $post->passwordNew);
        return $model->save();
    }

    /**
     *
     * @param string $startTime
     * @param string $endTime
     *
     * @return int
     */
    public function countByRegTime(string $startTime, string $endTime)
    {
        $query = UsersModel::query();
        if (!empty($startTime)) {
            $query->where('create_time', '>=', $startTime);
        }
        if (!empty($endTime)) {
            $query->where( 'create_time' , '<=', $endTime);
        }
        return $query->where('is_disabled', '=', CommonEnum::IS_DISABLED_NO)->count();
    }


    /**
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
     *
     * @param int $userId
     * @param $amount
     */
    public function balanceAdd(int $userId, $amount)
    {
        $sql = 'UPDATE '.UserBalanceModel::singleton()->getTable().' SET balance=balance+? WHERE user_id=?';
        return DB::update($sql, [ $amount, $userId ]);
    }
}