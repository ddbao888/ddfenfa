<?php


namespace App\Repository\Zds;


use App\Model\Zds\QuestionItem;
use App\Repository\BaseRep;

class QuestionItemRep extends BaseRep
{
    public function  list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = QuestionItem::where(function($query)use($condition){
           if(isset($condition['title']) && $condition['title']){
               $query->where('title', 'like', '%'. $condition['title'] .'%');
           }
            if(isset($condition['status']) && $condition['status']){
                $query->where('status', $condition['status']);
            }
            if(isset($condition['question_id']) && $condition['question_id']){
               $query->where('question_id', $condition['question_id']);
           }
        });
        return $this->modelPaginate($model, $condition, $order, $asc, $isPaginate);
    }
}