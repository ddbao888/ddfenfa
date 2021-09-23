<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class Setting extends BaseModel
{
    use Uuids;
    protected  $table = 'zds_settings';

}