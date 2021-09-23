<?php


namespace App\Http\Controllers\Api\Transformer\Zds;



use App\Model\Zds\MemberAnswer;
use App\Model\Zds\QuestionItem;
use App\Repository\Zds\MemberAnswerLogRep;
use League\Fractal\TransformerAbstract;

class MemberAnswerTransformer extends TransformerAbstract
{
    public function transform(MemberAnswer $answerCount)
    {
        return [
            'record_time' => $answerCount->created_at ? $answerCount->created_at->toDateString() : '',
            'question_name' => $answerCount->question->question_title,
            'success_rate' => $this->getSuccessRate($answerCount->id),
            'complete_rate' => $this->getSuccessRate($answerCount->id, $answerCount->question_id)
        ];
    }

    public function getSuccessRate($memberAnswerId)
    {
        $answerCount = MemberAnswerLogRep::count($memberAnswerId);
        $successNum = MemberAnswerLogRep::successNum($memberAnswerId);
        $successRate = $successNum == 0 || $answerCount == 0  ? 0 : round($successNum/$answerCount, 2);
        $successRate = intval($successRate * 100);
        return $successRate;
    }

    public function getCompleteRate($memberAnswerId, $questionId)
    {
        $questionItemCount =QuestionItem::where('question_id', $questionId)->where('status', 2)->count();
        $answerCount = MemberAnswerLogRep::count($memberAnswerId);
        $completeRate = $answerCount==0 || $questionItemCount ==0 ? 0 : round($answerCount/$questionItemCount, 2);
        $completeRate = intval($completeRate * 100);
        return $completeRate;
    }
}