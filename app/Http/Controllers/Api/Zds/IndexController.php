<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Resources\Zds\Question;
use App\Model\Zds\MpBanner;
use App\Model\Zds\Setting;

class IndexController extends BaseController
{
    public function index()
    {
        $setting = Setting::where('unicid', 1)->first();
        $basic = json_decode($setting->basic, 'true');
 
        return $this->success("", ['notice' => isset($basic['notice']) ? $basic['notice']: '无' , 'desc' => $basic['desc'], 'share' => $setting->wx_share]);
    }
}