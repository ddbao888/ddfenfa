<?php


namespace App\Repository\Zds;


use App\Model\Zds\MemberAnswerlog;

class MemberAnswerLogRep
{
    public static function count($memberAnswerId)
    {
        return MemberAnswerlog::where('member_answer_id', $memberAnswerId)->count();
    }

    public static function successNum($memberAnswerId)
    {
        return MemberAnswerlog::where('member_answer_id', $memberAnswerId)->where('is_success', 1)->count();
    }
}