<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Backend\BannerEditPost;
use App\Http\Requests\Backend\BannerListPost;

interface BannerRepositoryInterface
{
    public function findById(int $id);
    public function deleteRow($id);
    public function createRow(BannerEditPost $post);
    public function findBySku(int $sku);
    public function findListByPage(BannerListPost $post, int &$totalRows);
}
