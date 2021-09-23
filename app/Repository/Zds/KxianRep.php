<?php


namespace App\Repository\Zds;


use App\Model\Zds\Kxian;
use App\Model\Zds\Level;
use App\Repository\BaseRep;

class KxianRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $this->pageSize = $pageSize;
        $model = Kxian::where(function($query)use($condition){
            if(isset($condition['currency_id']) && $condition['currency_id']) {
                $query->where('currency_id', $condition['currency_id']);
            }
        });

        return $this->modelPaginate($model,$condition, $order, $asc, $isPaginate);
    }
}