<?php


namespace App\Repository\Zds;


use App\Model\Zds\Level;
use App\Repository\BaseRep;

class LevelRep extends BaseRep
{
   public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
   {
     $this->pageSize = $pageSize;
     $model = Level::where(function($query)use($condition){
         if(isset($condition['level_name']) && $condition['level_name']) {
             $query->where('level_name', 'like', '%'. $condition['level_name'] .'%');
         }
     });

     return $this->modelPaginate($model,$condition, $order, $asc, $isPaginate);
   }

   public function toJson($condition = [])
   {
       $model = Level::where(function($query)use($condition){
           if($condition['status'] && $condition['status']) $query->where('status', $condition['status']);
       });

       return $this->modelToJson($model, $condition,["id", "level_name"]);

   }
}