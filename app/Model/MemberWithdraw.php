<?php


namespace App\Model;


use App\Model\Zds\BaseModel;
use App\Model\Zds\Member;
use Emadadly\LaravelUuid\Uuids;

class MemberWithdraw extends BaseModel
{
    use Uuids;

    protected  $guarded = [];


    public function member()
    {
        return $this->belongsTo(Member::class, 'uid');
    }
}
