<?php


namespace App\Http\Controllers\Api\Zds;


use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use Helpers;

    const UNICID =1;
    public function success($msg = '成功!',  $data = null)
    {
        return $this->resultJson('success', $msg, $data, 200);
    }

    public function error($msg = '失败', $data = null)
    {
        return $this->resultJson('error', $msg, $data, 500);
    }

    private function resultJson($status, $msg,  $data = null, $code)
    {
        return response()->json(['status' => $status, 'code' => $code, 'msg' => $msg, 'data' => $data]);
    }

}