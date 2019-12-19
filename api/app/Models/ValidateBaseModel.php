<?php
/**
 * Description:
 * Author: zhaobin
 * Date: 2019-02-25
 * Time: 20:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

abstract class ValidateBaseModel extends Model
{
    use FindListTrait;
    /**
     * 白名单控制
     * @param array $attributes
     * @param array $fillable
     */
    public function __construct(array $attributes = [], array $fillable = null)
    {
        // 子模型白名单
        if ($fillable)
            $this->fillable($fillable);

        parent::__construct($attributes);
    }

    /**
     * Description:获取验证规则
     * Author: zhaobin
     * Date: 2019-02-25
     *
     * 'title' => 'required|unique:posts|max:255',
    'body' => 'required'
     * @return mixed
     */
    public function getRules()
    {
        return [];
    }

    /** @var array */
    protected $failed;
    /** @var MessageBag */
    protected $messageBag;
    /**
     * Description: 验证数据是否符合格式要求，在入库前调用判断
     * Author: zhaobin
     * Date: 2019-02-25
     * @return bool true=验证通过,false=验证失败
     */
    public function validate()
    {
        $validator = Validator::make($this->getAttributes(), $this->getRules());
        $ret = $validator->fails();
        if ($ret) {
            //存储错误消息
            $this->messageBag = $validator->getMessageBag();
            $this->failed = $validator->failed();
        }
        return !$ret;
    }

    /**
     *
     * Author: zhaobin
     * Date: 2019-02-26
     * @return MessageBag
     */
    public function getValidateError()
    {
        return $this->messageBag;
    }
    /**
     *
     * Author: zhaobin
     * Date: 2019-02-26
     * @return array
     */
    public function getFailed()
    {
        return $this->failed;
    }

}
