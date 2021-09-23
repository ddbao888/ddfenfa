<?php


namespace App\Repository\Zds;


use App\Model\Zds\Good;
use App\Repository\BaseRep;

class GoodRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = Good::where(function($query)use($condition){
            if(isset($condition['good_name']) && $condition['good_name']) $query->where('good_name', 'like', $condition['good_name']);
        });
        return $this->modelPaginate($model, $condition, $order, $asc, $isPaginate);
    }
}