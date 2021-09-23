<?php


namespace App\Repository;


use App\Model\MemberWithdraw;

class MemberWithdrawRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $modle = MemberWithdraw::where(function($query)use($condition){
            if(isset($condition['status']) && $condition['status']) {
                $query->where('status', $condition['status']);
            }
        });
        return $this->modelPaginate($modle, $condition, $order, $asc);
    }
}