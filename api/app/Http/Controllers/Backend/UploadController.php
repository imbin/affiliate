<?php
/**
 * Author: zhaobin
 * Date: 2019-11-17
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ImageUploadPost;
use App\Utils\UtilHelper;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function actionImage(ImageUploadPost $post)
    {
        $fileName = date('YmdHis-').(explode('.',microtime(1))[1]).mt_rand(100,999);
        $path = Storage::putFileAs(
            '/img/'.date('Y'), $post->file('image'), $fileName.'.'.$post->file('image')->extension()
        );

        $url = Storage::url($path);
        return $this->jsonSuccess([
            'file_url' => $url,
            'thumb_url' => UtilHelper::thumbUrl( $url)
        ]);
    }
}