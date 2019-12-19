<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-07
 * Time: 00:02
 */

namespace App\Http\Controllers\Backend;


use App\Enum\CodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminUserEditPost;
use App\Http\Requests\Backend\EditPasswordPost;
use App\Http\Requests\Backend\RegisterAdminPost;
use App\Http\Requests\BasePageListPost;
use App\Models\AdminUserModel;
use App\Services\Backend\AdminUserService;
use App\Utils\UtilHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminUserController extends Controller
{
    /**
     * 列表
     * Author: zhaobin
     * Date: 2019-11-13
     *
     * @param BasePageListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(BasePageListPost $post)
    {
        $totalRows = 0;
        $list = AdminUserService::singleton()->findListByPage($post, $totalRows);

        return $this->jsonSuccess([
            'list' => $list,
            'page' => $post->page,
            'perPage' => $post->perPage,
            'totalRows' => $totalRows
        ]);
    }

    /**
     * 修改账号
     * Author: zhaobin
     * Date: 2019-11-14
     *
     * @param AdminUserEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEdit(int $id, AdminUserEditPost $post)
    {
        if (0 > $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $myid = Auth::id();
        if ($myid == $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '不能编辑自己');
        }
        $model = AdminUserService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        if ($model->user_name != $post->userName) {
            $other = AdminUserService::singleton()->findByUserName( $post->userName);
            if ($other) {
                return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, $post->userName.'账号已存在');
            }
        }
        $ret = AdminUserService::singleton()->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 修改账号
     * Author: zhaobin
     * Date: 2019-11-14
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionDelete(int $id)
    {
        $myid = Auth::id();

        if ($id < 1) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '参数错误');
        } elseif ($myid == $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '不能删除自己');
        }
        $model = AdminUserService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = AdminUserService::singleton()->deleteRow($id);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }
    /**
     * 前台登录
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionLogin(Request $request)
    {
        $userName = $request->post('userName');
        $password = $request->post('password');

        $userModel = AdminUserService::singleton()->login( $userName);
        if (empty($userModel) || (false === UtilHelper::validPassword( $password, $userModel->passwd))) {
            $code = CodeEnum::USER_INVALID_ACCOUNT;
            $msg = __('user.invalid_account');
            return $this->jsonFail( $code, $msg);
        }
        // 生成token
        $token = JWTAuth::fromUser($userModel);
        return $this->jsonSuccess([
            'token' => $token,
            'realName' => $userModel->real_name
        ]);

    }

    /**
     * 创建账号
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param RegisterAdminPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionCreate(RegisterAdminPost $post)
    {
        $userModel = AdminUserService::singleton()->findByUserName( $post->userName);
        if ($userModel) {
            //已存在
            $code = CodeEnum::USER_NAME_EXISTS;
            $msg = __('user.name_exists');
            return $this->jsonFail( $code, $msg);
        }
        $ret = AdminUserService::singleton()->registerNew( $post);
        if (!$ret) {
            $code = CodeEnum::BASE_SERVER_ERROR;
            $msg = __('base.server_error');
            return $this->jsonFail( $code, $msg);
        }
        return $this->jsonSuccess();
    }

//    public function actionGetProfile()
//    {
//        /** @var AdminUserModel $user */
//        $user = Auth::user();
//        return $this->jsonSuccess([
//            'name' => $user->name,
//        ]);
//    }

    /**
     * 修改自己的密码
     * Author: zhaobin
     * Date: 2019-11-11
     *
     * @param EditPasswordPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEditPwd(EditPasswordPost $post)
    {
        /** @var AdminUserModel $user */
        $user = Auth::user();

        if ($post->passwordOld && false === UtilHelper::validPassword($post->passwordOld, $user->passwd)) {
            $code = CodeEnum::USER_INVALID_OLD_PWD;
            return $this->jsonFail( $code, __('user.invalid_old_pwd'));
        }

        $ret = AdminUserService::singleton()->editPassword( $post, $user);
        if (! $ret) {
            $code = CodeEnum::BASE_SERVER_ERROR;
            return $this->jsonFail( $code, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }
}