<?php


namespace App\Repository;


abstract  class BaseRep
{
    protected $pageSize = 15;

    public abstract function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15);

    protected function modelToJson($model, $condition = [], $select, $order='id', $asc = 'desc')
    {
        return $model->where(function($query)use($condition){
            if(isset($condition['is_plat_manager']) && $condition['is_plat_manager'] !=0) {
                $query->where('uid', $condition['uid']);
            }
        })->where('unicid', $condition['unicid'])->select($select)->get();
    }

    protected function modelPaginate($model, $condition=[], $order='id', $asc = 'desc', $isPaginate = true)
    {
        $data = $model->where(function($query)use($condition){
            if(isset($condition['is_plat_manager']) && !$condition['is_plat_manager']) {
                $query->where('uid', $condition['uid']);
            }
        });
        return $isPaginate ? $data->orderBy($order, $asc)->paginate($this->pageSize) : $data->orderBy($order, $asc)->get();
    }
}