<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Frontend;


use App\Enum\UserEnum;
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
     *
     * @param $userName
     *
     * @return UsersModel|null
     */
    public function login($userName)
    {
        $model = $this->findByUserName( $userName);
        if (!$model) {
            $model = $this->findByMobile( $userName);
            if (!$model) {
                $model = $this->findByEmail( $userName);
                if (!$model) {
                    return null;
                }
            }
        }
        $model->incrementLoginCount();
        return $model;
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
        $model = UsersModel::singleton()->findByEmail( $userName);
        if ($model) {
            return $model;
        }
        return null;
    }

    public function findByUserName($userName)
    {
        return UsersModel::singleton()->findByUserName( $userName);
    }

    public function registerNew(RegisterPost $post)
    {
        DB::beginTransaction();
        try {
            $model = new UsersModel();
            $model->user_name = $post->userName;
            $model->passwd = UtilHelper::hashPassword( $post->password );
            $model->status = UserEnum::STATUS_PENDING;

            $model->save();

            UserBalanceModel::singleton()->createRow($model->id);

            return true;
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return false;
        }
    }

    public function editProfile(EditProfilePost $post, UsersModel $model)
    {
        if ($post->birthday) $model->birthday = $post->birthday;
        if ($post->gender) $model->gender = $post->gender;
        if ($post->email) $model->email = $post->email;
        if ($post->mobile) $model->mobile = $post->mobile;
        return $model->save();

    }

    public function editPassword(EditPasswordPost $post, UsersModel $model)
    {
        $model->passwd = UtilHelper::hashPassword( $post->passwordNew);
        return $model->save();
    }
}