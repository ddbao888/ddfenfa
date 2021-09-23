<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class MpBanner extends BaseModel
{
    use Uuids;
    protected  $table = "zds_mp_banners";
}