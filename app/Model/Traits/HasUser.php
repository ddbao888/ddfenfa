<?php


namespace App\Model\Traits;


use App\Model\MgUser;

trait HasUser
{
    public function user()
    {
        return $this->belongsTo(MgUser::class, 'uid');
    }
}