<?php


namespace App\Repository;


use App\Model\Attachment;

class AttachmentRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $model = Attachment::where(function($query)use($condition) {
               if(isset($condition['group_id']) && $condition['group_id']) {
                   $query->where('attachment_group_id', $condition['group_id']);
               }
            })->where('type', $condition['type']);
        return $this->modelPaginate($model,$condition, $order, $asc);
    }




}