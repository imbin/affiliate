<?php

namespace App\Http\Requests\Backend;

use App\Enum\CommonEnum;
use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $sn string
 * @property $type string
 * @property $user_id string
 */
class TradeListPost extends BasePageListPost
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
            'user_id' => 'nullable|integer',
            'type' => 'nullable|in:'.implode(',', [
                CommonEnum::TRADE_TYPE_INCOME,
                CommonEnum::TRADE_TYPE_EXPEND,
            ]),
        ];
    }

    public function attributes()
    {
        return [
            'sn' => '交易单号',
            'type' => '交易类型',
            'user_id' => '用户ID',
        ];
    }
}
