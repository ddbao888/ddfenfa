<?php


namespace App\Repository\Zds;


use App\Model\Zds\Setting;

class SettingRep
{
    public function info($unicid, $id =null, $uid = null)
    {
        return Setting::where('unicid', $unicid)->first();

    }

    public static function first($unicid)
    {
        return Setting::where('unicid', $unicid)->first();
    }
}