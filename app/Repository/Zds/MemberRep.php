<?php


namespace App\Repository\Zds;


use App\Model\Zds\Member;
use App\Repository\BaseRep;
use Carbon\Carbon;

class MemberRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $this->pageSize = $pageSize;
        $model = Member::where(function($query)use($condition){
            if(isset($condition['nick_name']) && $condition['nick_name']) $query->where('nick_name', 'like', '%'. $condition['nick_name'] .'%');
            if(isset($condition['level_id']) && $condition['level_id']) $query->where('level_id', $condition['level_id']);
            if(isset($condition['date']) && $condition['date']){
                $query->where('addtime','>=',strtotime($condition['date'][0]))->where('addtime', '<=', strtotime($condition['date'][1]));
            }
        });
       return $this->modelPaginate($model, $condition, $order, $asc, $isPaginate);
        // TODO: Implement list() method.
    }
}