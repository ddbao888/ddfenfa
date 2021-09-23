<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class MemberRewardRecord extends BaseModel
{
    CONST WAIT = 1, HANDLED = 2, NO_PASS = 3;
    use Uuids;
    protected $table = 'zds_member_reward_records';
    public $statusArr = ['待处理', '已处理', '已取消'];
    protected $appends = ['status_name'];
    public function good()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

    public function getStatusNameAttribute()
    {
        return isset($this->status) ? $this->statusArr[$this->status] : '无状态';
    }
}
