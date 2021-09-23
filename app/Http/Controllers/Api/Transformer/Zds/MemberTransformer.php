<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\Member;
use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
    public function transform(Member $member)
    {
        return [
            'nick_name' => $member->nick_name,
            'avatar' => $member->avatar,
            'gold' => $member->gold,
            'credit' => $member->credit,
        ];
    }
}