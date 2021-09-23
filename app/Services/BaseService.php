<?php


namespace App\Services;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BaseService
{
    protected $rules,$messages;

    function dataValidator($data)
    {
        $validator = Validator::make($data, $this->rules, $this->messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function success($msg,$code = 200, $data =null)
    {
        return response()->json(['status' => 'success','msg' => $msg, 'code' => $code, 'data' => $data]);
    }

    public function error($msg, $code = 500, $data =null)
    {
        return response()->json(['status' => 'error','msg' => $msg, 'code' => $code, 'data' => $data]);
    }

    public static function returnResult($msg, $status = 'success', $code = 200)
    {
        return response()->json(['status' => $status,'msg' => $msg, 'code' => $code], $code);
    }
}