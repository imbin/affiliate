<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-07
 * Time: 00:02
 */

namespace App\Http\Controllers\Backend;


use App\Enum\CodeEnum;
use App\Enum\CommonEnum;
use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserEditPost;
use App\Http\Requests\Backend\UserListPost;
use App\Services\Backend\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 列表
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-13
     *
     * @param UserListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(UserListPost $post)
    {
        $totalRows = 0;
        $list = UserService::singleton()->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->statusText = UserEnum::STATUS_TEXT_LIST[$item->status] ?? '';
            $item->disableText = CommonEnum::DISABLED_TEXT_LIST[$item->is_disabled] ?? '';
        }

        return $this->jsonSuccess([
            'list' => $list,
            'page' => $post->page,
            'perPage' => $post->perPage,
            'totalRows' => $totalRows
        ]);
    }

    /**
     * 修改
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     * @param UserEditPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionEdit(int $id, UserEditPost $post)
    {
        if (0 >= $id) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $model = UserService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = UserService::singleton()->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 操作联盟客状态：审核通过、驳回、禁用、启用
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-14
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionStatus(int $id, Request $request)
    {
        $action = $request->input('action');
        if ($id < 1 || empty($action)) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '参数错误');
        }
        $model = UserService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }

        switch ($action) {
            case 'enable'://启用
                $ret = UserService::singleton()->disableOrEnableRow($model, CommonEnum::IS_DISABLED_NO);
                break;
            case 'disable'://禁用
                $ret = UserService::singleton()->disableOrEnableRow($model, CommonEnum::IS_DISABLED_YES);
                break;
            case 'pass'://审核通过
                $ret = UserService::singleton()->updateStatusRow($model, UserEnum::STATUS_PASS);
                break;
            case 'reject'://驳回
                $ret = UserService::singleton()->updateStatusRow($model, UserEnum::STATUS_REJECT);
                break;
            default:
                $ret = false;
                break;
        }
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }


}