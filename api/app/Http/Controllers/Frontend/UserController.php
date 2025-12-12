<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:02
 */

namespace App\Http\Controllers\Frontend;


use App\Enum\CodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditPasswordPost;
use App\Http\Requests\EditProfilePost;
use App\Http\Requests\RegisterPost;
use App\Models\UsersModel;
use App\Services\Frontend\UserService;
use App\Utils\UtilHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * 前台登录
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionLogin(Request $request)
    {
        $userName = $request->post('userName');
        $password = $request->post('password');

        $userModel = $this->userService->login( $userName);
        if (empty($userModel) || (false === UtilHelper::validPassword( $password, $userModel->passwd))) {
            $code = CodeEnum::USER_INVALID_ACCOUNT;
            $msg = __('user.invalid_account');
            return $this->jsonFail( $code, $msg);
        }
        if ($userModel->isPassed()) {
            // 生成token
            $token = JWTAuth::fromUser($userModel);
            return $this->jsonSuccess([
                'token' => $token,
                'uid' => $userModel->id,
            ]);
        }
        //待审核
        if ($userModel->isPending()) {
            $code = CodeEnum::USER_STATUS_PENDING;
            $msg = __('user.status_pending');
            return $this->jsonFail( $code, $msg);
        }

        //驳回
        list($code, $key) = CodeEnum::USER_INVALID_ACCOUNT;
        $msg = Lang::get($key);
        return $this->jsonFail( $code, $msg);

    }

    /**
     * 前台登录
     *
     * @param RegisterPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionRegister(RegisterPost $post)
    {
        $userModel = $this->userService->findByUserName( $post->userName);
        if ($userModel) {
            //已存在
            $code = CodeEnum::USER_NAME_EXISTS;
            $msg = __('user.name_exists');
            return $this->jsonFail( $code, $msg);
        }
        $ret = $this->userService->registerNew( $post);
        if (!$ret) {
            $code = CodeEnum::BASE_SERVER_ERROR;
            $msg = __('base.server_error');
            return $this->jsonFail( $code, $msg);
        }
        return $this->jsonSuccess();
    }

    public function actionGetProfile()
    {
        /** @var UsersModel $user */
        $user = Auth::user();
        return $this->jsonSuccess([
            'userName' => $user->user_name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'gender' => $user->gender,
            'birthday' => $user->birthday
        ]);
    }

    /**
     * 修改个人资料
     *
     * @param EditProfilePost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEditProfile(EditProfilePost $post)
    {
        /** @var UsersModel $user */
        $user = Auth::user();

        if ($post->email && $post->email != $user->email) {
            $exists = $this->userService->findByEmail( $post->email );
            if ($exists) {
                $code = CodeEnum::USER_EMAIL_EXISTS;
                return $this->jsonFail( $code, __('user.email_exists'));
            }
        }
        if ($post->mobile && $post->mobile != $user->mobile) {
            $exists = $this->userService->findByMobile( $post->mobile );
            if ($exists) {
                $code = CodeEnum::USER_EMAIL_EXISTS;
                return $this->jsonFail( $code, __('user.mobile_exists'));
            }
        }

        $ret = $this->userService->editProfile( $post, $user);
        if (!$ret) {
            $code = CodeEnum::BASE_SERVER_ERROR;
            return $this->jsonFail( $code, __('base.server_error'));

        }
        return $this->jsonSuccess();
    }

    /**
     * 修改密码
     *
     * @param EditPasswordPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEditPwd(EditPasswordPost $post)
    {
        /** @var UsersModel $user */
        $user = Auth::user();

        if (false === UtilHelper::validPassword($post->passwordOld, $user->passwd)) {
            $code = CodeEnum::USER_INVALID_OLD_PWD;
            return $this->jsonFail( $code, __('user.invalid_old_pwd'));
        }

        $ret = $this->userService->editPassword( $post, $user);
        if (! $ret) {
            $code = CodeEnum::BASE_SERVER_ERROR;
            return $this->jsonFail( $code, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }
}