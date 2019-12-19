<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 23:51
 */

namespace App\Models;
//namespace App\Models;

use App\Enum\BannerEnum;
use App\Enum\UserEnum;
use App\Utils\UtilHelper;
use Illuminate\Database\Eloquent\Model;
use App\Models\ValidateBaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $pic_url 图片URL
 * @property int $status 状态：1=已上架、2=已下架
 * @property string $redirect_url 跳转 URL
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 * @property string $sku SKU
 * @property int $return_type 返佣方式:1=金额,2=比例
 * @property float $return_value 返佣金额/比例
 * @property float $price 售价
 */
class BannerModel extends ValidateBaseModel
{

    protected $table = 'banner';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则
     * Author: xxx
     * Date: 2019-02-25
     *
     * 'title' => 'required|max:255',
     * @return array
     */
    public function getRules()
    {
        return [
            //'merchant_id' => 'required|integer',
            //'title' => 'required|max:255',
        ];
    }

    public function formatReturnType()
    {
        return $this->return_type == BannerEnum::RETURN_TYPE_AMOUNT ? '￥'.$this->return_value : $this->return_value.'%';
    }

    public function isReturnAmount()
    {
        return $this->return_type == BannerEnum::RETURN_TYPE_AMOUNT;
    }
    public function isReturnPercent()
    {
        return $this->return_type == BannerEnum::RETURN_TYPE_PERCENT;
    }

    /**
     * Author: zhaobin
     * Date: 2019-11-24
     *
     * @param int $sku
     *
     * @return $this
     */
    public function findBySku(int $sku)
    {
        return static::query()->where('sku','=', $sku)->first();
    }
}
