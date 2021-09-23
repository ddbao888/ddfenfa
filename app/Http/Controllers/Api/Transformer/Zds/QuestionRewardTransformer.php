<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\QuestionReward;
use League\Fractal\TransformerAbstract;

class QuestionRewardTransformer extends TransformerAbstract
{
    public function transform(QuestionReward $reward)
    {
        return [
            'good_name' => $reward->good->good_name,
            'pic' => $reward->good->pic,
        ];
    }
}