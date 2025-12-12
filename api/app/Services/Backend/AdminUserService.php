<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:03
 */

namespace App\Services\Backend;


use App\Enum\UserEnum;
use App\Http\Requests\Backend\AdminUserEditPost;
use App\Http\Requests\Backend\EditPasswordPost;
use App\Http\Requests\Backend\RegisterAdminPost;
use App\Http\Requests\BasePageListPost;
use App\Models\AdminUserModel;
use App\Services\BaseService;
use App\Utils\UtilHelper;

class AdminUserService extends BaseService
{
/**
     *
     * @param BasePageListPost $post
     * @param int $totalRows
     *
     * @return AdminUserModel[]
     */
    public function findListByPage(BasePageListPost $post, int &$totalRows)
    {
        $list = AdminUserModel::query()->forPage( $post->page, $post->perPage)->get()->all();
        if (count($list)) {
            $totalRows = AdminUserModel::query()->count();
        }
        return $list;
    }

    public function editRow(AdminUserModel $model, AdminUserEditPost $post)
    {
        $model->user_name = $post->userName;
        $model->real_name = $post->realName;
        if ($post->password) {
            $model->passwd = UtilHelper::hashPassword( $post->password);
        }
        return $model->save();
    }

/**
     *
     * @param $id
     *
     * @return int
     */
    public function deleteRow($id)
    {
        return AdminUserModel::query()->where('id', $id)->delete();
    }

    /**
     *


     *
     * @param $userName
     *
     * @return AdminUserModel|null
     */
    public function login($userName)
    {
        $model = $this->findByUserName( $userName);
        if (!$model) {
            return null;
        }
        return $model;
    }

/**
     *
     * @param int $id
     *
     * @return AdminUserModel
     */
    public function findById(int $id)
    {
        return AdminUserModel::singleton()->findById( $id);
    }
    public function findByUserName($userName)
    {
        return AdminUserModel::singleton()->findByUserName( $userName);
    }

    public function registerNew(RegisterAdminPost $post)
    {
        $model = new AdminUserModel();
        $model->user_name = $post->userName;
        $model->real_name = $post->realName;
        $model->passwd = UtilHelper::hashPassword( $post->password);
        return $model->save();
    }

    public function editPassword(EditPasswordPost $post, AdminUserModel $model)
    {
        if ($post->passwordNew && $post->passwordOld) {
            $model->passwd = UtilHelper::hashPassword( $post->passwordNew );
        }

        $model->real_name = $post->realName;

        return $model->save();
    }
}