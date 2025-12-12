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
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * 列表
     *
     * @param UserListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(UserListPost $post)
    {
        $totalRows = 0;
        $list = $this->userService->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->status_text = UserEnum::STATUS_TEXT_LIST[$item->status] ?? '';
            $item->disable_text = CommonEnum::DISABLED_TEXT_LIST[$item->is_disabled] ?? '';
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
        $model = $this->userService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = $this->userService->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 操作联盟客状态：审核通过、驳回、禁用、启用
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
        $model = $this->userService->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }

        switch ($action) {
            case 'enable'://启用
                $ret = $this->userService->disableOrEnableRow($model, CommonEnum::IS_DISABLED_NO);
                break;
            case 'disable'://禁用
                $ret = $this->userService->disableOrEnableRow($model, CommonEnum::IS_DISABLED_YES);
                break;
            case 'pass'://审核通过
                $ret = $this->userService->updateStatusRow($model, UserEnum::STATUS_PASS);
                break;
            case 'reject'://驳回
                $ret = $this->userService->updateStatusRow($model, UserEnum::STATUS_REJECT);
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