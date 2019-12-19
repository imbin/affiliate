<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $way string
 * @property $card string
 * @property $name string
 * @property $amount string
 */
class WithdrawCreatePost extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'way' => 'required|in:1,2',
            'card' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric|min:100',
        ];
    }
}
