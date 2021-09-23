<?php


namespace App\Http\Controllers\Zds;


use App\Http\Controllers\Controller;
use App\Repository\Zds\SettingRep;
use App\Services\Zds\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseSettingController extends BaseController
{
    protected $service, $rep;
    public function __construct(SettingService $service, SettingRep $rep)
    {
        $this->service = $service;
        $this->rep = $rep;
    }

    public function index()
    {
        return view('zds.setting.index');
    }

    public function info(Request $request)
    {
        $user = Auth::guard('web')->user();
        return $this->success('获取成功', $this->rep->info($user->unicid));
    }

    public function storage()
    {
        $setting = $this->rep->info(1);
        return $this->success('获取成功', ['type' => $setting->storage_type, 'tengxun'=> $setting->storage]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::guard('web')->user();
        return $this->service->save($data, $user->unicid, $user->id);

    }
}