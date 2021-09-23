<?php


namespace App\Services;


use App\Model\MemberWithdraw;
use App\Model\Zds\Member;
use App\Services\Zds\MemberService;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MemberWithdrawService extends BaseService
{
    public static function add($uid, $credit)
    {
        $user = Member::where('id', $uid)->first();
        if($user->credit < $credit) {
            return self::returnResult('提现失败，超额', 'error');
        }
       DB::beginTransaction();
        try
        {
            $memberWithdraw = MemberWithdraw::create(['uid' => $user->id,'credit' => $credit, 'total_credit' => $user->credit, 'replytime' => time(), 'uuid' => Uuid::uuid4()->toString()]);
            
            MemberService::addCredit($user ,-$credit, $memberWithdraw->id, 'withdraw');

            DB::commit();
            return self::returnResult('申请成功!');

        }catch(\Exception $exception)
        {
            DB::rollBack();
            return self::returnResult('提现失败'.$exception->getMessage(), 'error');
        }

    }
}