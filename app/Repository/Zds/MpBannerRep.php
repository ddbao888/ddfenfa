<?php


namespace App\Repository\Zds;


use App\Model\Zds\MpBanner;
use App\Repository\BaseRep;

class MpBannerRep extends BaseRep
{
    public function list($condition = [], $order = 'id', $asc = 'desc', $isPaginate = true, $pageSize = 15)
    {
        $this->pageSize = $pageSize;
        $model = MpBanner::where('uid', $condition['uid'])->where('unicid', $condition['unicid']);
        return $this->modelPaginate($model,$condition, $order, $asc, $isPaginate);
    }
}