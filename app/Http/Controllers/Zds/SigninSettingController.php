<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\SigninSettingCollection;
use App\Model\Zds\SignSetting;
use App\Services\SignSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SigninSettingController extends BaseController
{
    public function index()
    {
        
        return $this->view('setting.signin.index');
    }

    public function list()
    {
        $user = Auth::guard('web')->user();
        $signSetting = SignSetting::where('uid', $user->id)->where('unicid', $user->unicid)->get();
        return new SigninSettingCollection($signSetting);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $auth = Auth::guard('web')->user();
        $service = new SignSettingService();
        return $service->store($auth->id, $auth->unicid, $data);
    }

    public function edit()
    {

    }

    public function destroy()
    {

    }


}