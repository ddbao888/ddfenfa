<?php


namespace App\Services\Zds;


use App\Model\Zds\QuestionLikeLog;

class QuestionLikeLogService extends BaseService
{
    public static function like($uid, $questionId)
    {
        $questionLikeLog = QuestionLikeLog::where('uid', $uid)->where('question_id', $questionId)->first();
        if($questionLikeLog)
        {
            QuestionLikeLog::where('uid', $uid)->where('question_Id', $questionId)->delete();
        } else {
            $questionLikeLog = new QuestionLikeLog();
            $questionLikeLog->uid = $uid;
            $questionLikeLog->question_id = $questionId;
            $questionLikeLog->save();
        }
        return true;
    }
}