<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\MemberAnswerCount;
use League\Fractal\TransformerAbstract;

class MemberAnswerCountTransformer extends TransformerAbstract
{
    public function transform(MemberAnswerCount $answerCount)
    {
        return [
            'title' => $answerCount->question->question_title,
            'num' => $answerCount->num,
            'success_num' => $answerCount->success_num,
            'rate' => $answerCount->success_num == 0 ? 0 : round(($answerCount->success_num/$answerCount->num)*100, 2)
        ];
    }
}