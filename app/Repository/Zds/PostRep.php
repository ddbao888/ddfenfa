<?php


namespace App\Repository\Zds;


use App\Model\Zds\Post;
use App\Repository\BaseRep;

class PostRep extends BaseRep
{
    public static function find($id)
    {
        return Post::where('id', $id)->firstOrFail();
    }

    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = Post::where(function($query)use($condition){
            if(isset($condition['title']) && $condition['title']) {
                $query->where('title', 'like', '%'.$condition.'%');
            }
        });
        return $this->modelPaginate($model, $condition);
    }
}