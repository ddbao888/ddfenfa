<?php


namespace App\Repository\Zds;


use App\Model\Zds\Question;
use App\Repository\BaseRep;

class QuestionRep extends BaseRep
{

    public static function first($uuid)
    {
        return Question::where('uuid', $uuid)->where('status', 2)->first();
    }

    public static function paginate()
    {
        return Question::where('status', 2)->orderBy('sort', 'desc')->paginate(20);
    }

    public function list($condition = [], $order = 'sort', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $this->pageSize = $pageSize;
        $model = Question::where(function($query)use($condition){
            if(isset($condition['status']) && $condition['status']){
                $query->where('status', $condition['status']);
            }
            if(isset($condition['title']) && $condition['title'])
            {
                $query->where('question_title', 'like', '%'. trim($condition['title']) .'%');
            }
            if(isset($condition['question_cate_id']) && $condition['question_cate_id'])
            {
                $query->where('question_cate_id', $condition['question_cate_id']);
            }
            if(isset($condition['begin_time']) && $condition['begin_time'] && isset($condition['end_time']) && $condition['end_time'])
            {
                $query->where('add_time','>=', $condition['begin_time'])->where('add_time', $condition['end_time']);
            }
        });
        return $this->modelPaginate($model, $condition, $order, $asc, $isPaginate);
    }

    public function toJson($condition = [], $select)
    {
        $model = Question::where(function($query)use($condition){
            if(isset($condition['status']) && $condition['status']){
                $query->where('status', $condition['status']);
            }
            if(isset($condition['title']) && $condition['title'])
            {
                $query->where('question_title', 'like', '%'. trim($condition['title']) .'%');
            }
            if(isset($condition['question_cate_id']) && $condition['question_cate_id'])
            {
                $query->where('question_cate_id', $condition['question_cate_id']);
            }
            if(isset($condition['begin_time']) && $condition['begin_time'] && isset($condition['end_time']) && $condition['end_time'])
            {
                $query->where('add_time','>=', $condition['begin_time'])->where('add_time', $condition['end_time']);
            }
        });
        return $this->modelToJson($model, $condition,$select);
    }
}