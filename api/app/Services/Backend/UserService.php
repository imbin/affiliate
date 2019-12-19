<?php
/**
 *
 * Author: zhaobin
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
     * Author: zhaobin
     * Date: 2019-11-14
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
     * Author: zhaobin
     * Date: 2019-11-14
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

    /**
     * Author: zhaobin
     * Date: 2019-11-14
     *
     * @param $id
     *
     * @return int
     */
    public function updateStatus($id, $val)
    {
        return UsersModel::query()->where('id', $id)->where('is_deleted', $val)->update([
            'is_deleted' => CommonEnum::IS_DISABLED_YES
        ]);
    }

    public function editRow(UsersModel $model, UserEditPost $post)
    {
        $model->passwd = UtilHelper::hashPassword( $post->password);

        return $model->save();
    }
    /**
     * Author: zhaobin
     * Date: 2019-11-14
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
     * Author: zhaobin
     * Date: 2019-11-24
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
     * Author: zhaobin
     * Date: 2019-11-13
     *
     * @param BasePageListPost $post
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

    public function findByMobile($userName)
    {
        if ( is_numeric( $userName ) && intval( $userName ) > 0 ) {
            $model = UsersModel::singleton()->findByMobile( $userName );
            if ( $model ) {
                return $model;
            }
        }

        return null;
    }
    public function findByEmail($userName)
    {
        $isEmail = filter_var($userName, FILTER_VALIDATE_EMAIL);
        if ($isEmail) {
            $model = UsersModel::singleton()->findByEmail( $userName);
            if ($model) {
                return $model;
            }
        }
        return null;
    }

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
     * Author: zhaobin
     * Date: 2019-11-16
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
     * Author: zhaobin
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
     * Author: zhaobin
     * Date: 2019-11-26
     *
     * @param int $userId
     * @param $amount
     */
    public function balanceAdd(int $userId, $amount)
    {
        $sql = 'UPDATE '.UserBalanceModel::singleton()->getTable().' SET balance=balance+? WHERE user_id=?';
        DB::update($sql, [ $amount, $userId ]);
    }
}