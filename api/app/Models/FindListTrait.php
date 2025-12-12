<?php
/**
 * Description:
 * @author: tobinzhao@gmail.com
 * Date: 2019-02-25
 * Time: 20:20
 */

namespace App\Models;

trait FindListTrait
{

    /**
     * 根据主键 ID 获取单个实例
     * Author: xxx     * @param $id int
     *
     * @return $this
     */
    public function findById($id)
    {
        return static::query()->find($id);
    }

    /**
     *
     * @param array $id
     *
     * @return $this[]
     */
    public function findListById(array $id)
    {
        return static::query()->whereIn('id', $id)->get()->all();
    }

    /**
     * 分页获取多个实例（Model数组）
     *
     * @param array $where
     * @param int $page
     * @param int $perPage
     * @param int $totalRows 返回查询总数
     *
     * @return static[]
     */
    public function findListByPage(array $where, $page, $perPage, &$totalRows, $orderByDesc = 'id' )
    {
        $query = static::query();
        $query->where($where)->forPage( $page, $perPage)->orderByDesc($orderByDesc);

        $list = $query->get()->all();
        $totalRows = $query->toBase()->getCountForPagination();
        return $list;
    }

    /**
     * @return $this
     */
    public static function singleton()
    {
        return app()->make(static::class);
    }
}
