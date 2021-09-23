<?php


namespace App\Repository\Zds;


use App\Model\Zds\Question;
use App\Model\Zds\QuestionCate;
use App\Repository\BaseRep;

class QuestionCateRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = QuestionCate::where(function($query)use($condition){
            if(isset($condition['title']) && $condition['title']) {
                $query->where('title', $condition['title']);
            }
        });
        return $this->modelPaginate($model, $condition, $order, $asc, $isPaginate);
    }
}