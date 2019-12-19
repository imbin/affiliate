<?php

namespace App\Http\Requests\Backend;

use App\Enum\CommonEnum;
use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WithdrawListPost
 * @package App\Http\Requests
 *
 * @property $sn string
 * @property $status string
 */
class WithdrawListPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sn' => 'nullable',
            'status' => 'nullable|in:'.implode(',', [
                CommonEnum::WITHDRAW_STATUS_PENDING,
                CommonEnum::WITHDRAW_STATUS_COMPLETE,
                CommonEnum::WITHDRAW_STATUS_REJECT,
            ]),
        ];
    }

    public function attributes()
    {
        return [
            'sn' => '提现单号',
            'status' => '提现状态',
        ];
    }
}
