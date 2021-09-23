<?php


namespace App\Http\Controllers\Zds;


use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use Helpers;

    protected $service, $rep, $data;
    public function view($page, $arg = [])
    {
        return view('zds.'.$page, $arg);
    }

    public function initData()
    {
        $auth = Auth::guard('web')->user();
        $this->data['uid'] = $auth->id;
        $this->data['unicid'] = $auth->unicid;
        $this->data['is_plat_manager'] = $auth->is_plat_manager;
    }

    public function success($msg = '成功!', $data = null)
    {
        return $this->resultJson('success', $msg, $data);
    }

    public function error($msg = '失败', $data = null)
    {
        return $this->resultJson('error', $msg, $data);
    }

    private function resultJson($status, $msg,  $data = null)
    {
       return response()->json(['status' => $status, 'msg' => $msg, 'data' => $data]);
    }

}