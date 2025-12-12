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
use App\Http\Requests\Backend\WithdrawListPost;
use App\Services\Backend\WithdrawService;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * 列表


     *
     * @param WithdrawListPost $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionList(WithdrawListPost $post)
    {
        $totalRows = 0;
        $list = WithdrawService::singleton()->findListByPage($post, $totalRows);

        foreach ($list as $item) {
            $item->setAttribute( 'statusText', CommonEnum::WITHDRAW_STATUS_TEXT_LIST[$item->status]);
            $item->setAttribute( 'wayText', CommonEnum::WITHDRAW_WAY_TEXT_LIST[$item->way]);
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
        $model = WithdrawService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '账号不存在');
        }
        $ret = WithdrawService::singleton()->editRow($model, $post);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }

    /**
     * 操作提现状态：审核通过、驳回


     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionUpdateStatus(int $id, Request $request)
    {
        $action = $request->input('action');
        if ($id < 1 || empty($action)) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '参数错误');
        }
        $model = WithdrawService::singleton()->findById( $id);
        if (!$model) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '提现单不存在');
        }

        $remark = $request->input('remark', '');
        switch ($action) {
            case 'pass'://审核通过
                $status = CommonEnum::WITHDRAW_STATUS_COMPLETE;
                break;
            case 'reject'://驳回
                $status = CommonEnum::WITHDRAW_STATUS_REJECT;
                break;
            default:
                return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '参数错误, 未知的操作:'.$action);
        }

        if ($model->status > CommonEnum::WITHDRAW_STATUS_PENDING) {
            return $this->jsonFail( CodeEnum::BASE_INVALID_PARAMETER, '提现单号状态已经变化, 请刷新');
        }

        $ret = WithdrawService::singleton()->updateStatusRow($model, $status, $remark);
        if (!($ret)) {
            return $this->jsonFail( CodeEnum::BASE_SERVER_ERROR, __('base.server_error'));
        }

        return $this->jsonSuccess();
    }


}