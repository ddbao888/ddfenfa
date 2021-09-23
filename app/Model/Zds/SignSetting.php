<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class SignSetting extends BaseModel
{
    use Uuids;
    protected  $table = 'signin_settings';

    public function good()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}