<?php


namespace App\Services\Zds;


use App\Model\Zds\Setting;

class SettingService extends BaseService
{

    public function save($data, $unicid, $uid)
    {
        $setting = Setting::where('unicid', $unicid)->first();
        if(!$setting) {
            $setting = new Setting();
        }

        $setting->buy_limit = $data['buy_limit'];
        $setting->scales = $data['scales'];
        $setting->desc = $data['desc'];
        $setting->uid = $uid;
        $setting->wx_share = $data['wx_share'];
        $setting->unicid = $unicid;
        $ret = $setting->save();
        if($ret) {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

}