<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\MemberRewardRecord;
use League\Fractal\TransformerAbstract;

class MemberRewardRecordTransformer extends TransformerAbstract
{
    public function transform(MemberRewardRecord $memberRewardRecord)
    {
        return [
            'good_name' => $memberRewardRecord->good->good_name,
            'type' => $memberRewardRecord->good->type,
            'pic' => $memberRewardRecord->good->pic,
            'created_at' => $memberRewardRecord->created_at->toDateTimeString(),
            'status' => $memberRewardRecord->status,
            'status_name' => $memberRewardRecord->status_name,
            'origin' => $this->origin($memberRewardRecord->origin_type)
        ];
    }

    public function origin($originType)
    {
        if($originType == 'question_reward')
        {
            return '答题奖励';
        }
        if($originType == 'signin')
        {
            return '签到奖励';
        }
    }
}