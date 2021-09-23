<?php


namespace App\Model;


use App\Model\Zds\BaseModel;
use Emadadly\LaravelUuid\Uuids;

class Comment extends BaseModel
{
    use Uuids;
    CONST PASS = 1;
    CONST NOPASS = -1;
    CONST WAIT = 0;

    public function replyList()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->getKeyName())->orderBy('id');
    }
}