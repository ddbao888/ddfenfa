<?php


namespace App\Services\Zds;


use App\Model\Zds\MemberAnswerCount;

class MemberAnswerCountService extends BaseService
{

    public static function recordNum($uid, $question_id, $useTime=0, $isRight)
    {
        $recordTime = date('Ymd', time());
        $memberAnswerCount = MemberAnswerCount::where('uid', $uid)->where('record_time', $recordTime)->first();
        $times = MemberAnswerCount::where('uid', $uid)->count();
        if(!$memberAnswerCount) {
            $memberAnswerCount = new MemberAnswerCount();
            $memberAnswerCount->uid = $uid;
            $memberAnswerCount->times = $times+1;
            $memberAnswerCount->record_time = $recordTime;
            $memberAnswerCount->use_time = $useTime;
            $memberAnswerCount->question_id = $question_id;
            $memberAnswerCount->num = 1;
            if($isRight) $memberAnswerCount->success_num = 1;
            $memberAnswerCount->save();
        } else {
            $memberAnswerCount->num = $memberAnswerCount->num +1;
            if($isRight) $memberAnswerCount->success_num = $memberAnswerCount->success_num+1;
            $memberAnswerCount->save();
        }
    }

    public static function recordSuccessNum($uid, $question_id)
    {
        $recordTime = date('Ymd', time());
        $memberAnswerCount = MemberAnswerCount::where('uid', $uid)->where('record_time', $recordTime)->first();
        $memberAnswerCount->success_num = $memberAnswerCount->success_num +1;
        $memberAnswerCount->save();
    }


}