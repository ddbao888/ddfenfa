<?php


namespace App\Http\Controllers\Api\Transformer\Zds;



use App\Model\MemberWithdraw;
use League\Fractal\TransformerAbstract;

class MemberWithdrawTransformer extends TransformerAbstract
{
    public function transform(MemberWithdraw $memberWithdraw)
    {
        return [
            'uuid' => $memberWithdraw->uuid,
            'credit' => $memberWithdraw->credit,
            'status' => $memberWithdraw->status,
            'reply_time' => $memberWithdraw->created_at->toDateTimeString()
        ];
    }
}