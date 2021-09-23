<?php


namespace App\Services\Zds;


use App\Model\Zds\BaseModel;
use App\Model\Zds\MemberAnswerlog;

class MemberAnswerlogService extends BaseService
{
    public static function add($uid, $question_item_id)
    {
        $memberAnswerlog = new MemberAnswerlog();
        $memberAnswerlog->uid = $uid;
        $memberAnswerlog->question_item_id = $question_item_id;
        $memberAnswerlog->addtime = time();
        $ret = $memberAnswerlog->save();
        return $ret ? true : false;
    }


    public static function setStatus($uid, $questionItemId, $status, $isSuccess = 0)
    {
        $memberAnswerlog = MemberAnswerlog::where('question_item_id', $questionItemId)->where('uid', $uid)->where('status', 0)->first();
        if($memberAnswerlog) {
            $memberAnswerlog->status = $status;
            $memberAnswerlog->is_success = $isSuccess;
            $memberAnswerlog->save();
            return true;
        }

        return false;

    }
}
