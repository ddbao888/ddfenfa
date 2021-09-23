<?php


namespace App\Repository;


use App\Model\MgUser;

class UserRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = MgUser::where(function($query)use($condition){
            if(isset($condition['user_name']) && $condition['user_name']) {
                $query->where('user_name', 'like', '%'.$condition['user_name'].'%');
            }
        });
        return $this->modelPaginate($model, $condition);
    }
}