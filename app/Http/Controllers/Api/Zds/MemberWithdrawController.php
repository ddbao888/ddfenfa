<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\MemberWithdrawTransformer;
use App\Model\MemberWithdraw;
use App\Services\MemberWithdrawService;
use Illuminate\Http\Request;

class MemberWithdrawController extends BaseController
{

    public function store(Request $request)
    {
        $credit = $request->get('credit');
        if(!$credit){
            return $this->error('提现金额不能为空!');
        }
        $user  = auth('api')->user();
        if($credit > $user->credit)
        {
            return $this->error('提现金额大于账户余额!');
        }
        return MemberWithdrawService::add($user->id, $credit);
    }

    //提现记录
    public function list(Request $request)
    {
        $user = auth('api')->user();
        $status = $request->get('status');
        $memberWithdraw = $user->withdraws()->where(function($query)use($status){
            if($status){
                $query->where('status', $status);
            }
        })->paginate(15);
        return $this->response()->paginator($memberWithdraw, new MemberWithdrawTransformer);
    }

}