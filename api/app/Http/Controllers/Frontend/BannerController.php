<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 22:59
 */

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Http\Requests\BasePageListPost;
use App\Services\Frontend\BannerService;
use App\Utils\UtilHelper;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function actionList(BasePageListPost $post)
    {
        $totalRows = 0;
        $list = BannerService::singleton()->findListByPage( $post, $totalRows);
        $ret = [];
        foreach ($list as &$item) {
            $ret[] = [
                'id' => $item->id,
                'title' => $item->title,
                'sku' => $item->sku,
                'price' => $item->price,
                'return_text' => $item->formatReturnType(),
                'redirect_url' => $item->redirect_url,
                'thumb_url' => UtilHelper::thumbUrl( $item->pic_url),
            ];
        }
        return $this->jsonSuccess([
            'list' => $ret,
            'total' => $totalRows,
        ]);
    }

}