<?php


namespace App\Services\Zds;


class BaseService
{
    const ISMANAGER = 1;

    public function success($msg,$code = 200, $data =null)
    {
        return response()->json(['status' => 'success','msg' => $msg, 'code' => $code, 'data' => $data]);
    }

    public function error($msg, $code = 500, $data =null)
    {
        return response()->json(['status' => 'error','msg' => $msg, 'code' => $code, 'data' => $data]);
    }
}